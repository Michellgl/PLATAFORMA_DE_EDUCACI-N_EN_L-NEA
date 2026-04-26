<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCourseController extends Controller
{
    /**
     * Carga el catálogo de cursos para el alumno.
     */
    public function index()
    {
        $courses = Course::with('teacher')->withAvg('reviews', 'rating')->get();
        return view('dashboard', compact('courses'));
    }

    /**
     * Procesa la inscripción manual (Crea el registro en la tabla pivote).
     */
    public function enroll(Course $course)
    {
        Auth::user()->courses()->syncWithoutDetaching([$course->id]);

        return redirect()->route('student.course', $course->id)
                         ->with('success', '¡Te has inscrito correctamente! Ahora puedes guardar tu progreso.');
    }

    /**
     * Muestra el reproductor (Con sistema Anti-Trampas condicional).
     */
    public function show(Course $course, Request $request)
    {
        $user = Auth::user();
        
        // Verificamos si el alumno ya dio clic en "Inscribirme"
        $isEnrolled = $user->courses->contains($course->id);

        $course->increment('views_count');

        $course->load(['lessons' => function($query) {
            $query->orderBy('sequence_order', 'asc');
        }]);

        if ($course->lessons->count() === 0) {
            return view('student.course', ['course' => $course, 'activeLesson' => null, 'isEnrolled' => $isEnrolled]);
        }

        // Definir la lección activa (la de la URL o la primera)
        $activeLesson = $course->lessons->first(); 

        if ($request->has('lesson')) {
            $requestedLesson = $course->lessons->where('id', $request->lesson)->first();
            
            if ($requestedLesson) {
                // ====== SISTEMA ANTI-TRAMPAS (Solo aplica si está inscrito) ======
                $index = $course->lessons->search(fn($item) => $item->id === $requestedLesson->id);

                if ($index > 0 && $isEnrolled) {
                    $prevLesson = $course->lessons[$index - 1];
                    
                    // Si está inscrito y la anterior NO está completada, lo bloqueamos
                    if (!$user->completedLessons->contains($prevLesson->id)) {
                        return redirect()->route('student.course', $course->id)
                                         ->with('error', 'Debes completar la lección anterior para avanzar en tu progreso oficial.');
                    }
                }
                $activeLesson = $requestedLesson;
            }
        }

        return view('student.course', compact('course', 'activeLesson', 'isEnrolled', 'user'));
    }

    /**
     * Marca una clase como completada y salta a la siguiente.
     */
    public function completeLesson(Lesson $lesson) 
    {
        $user = Auth::user();
        $course = $lesson->course;

        // Seguridad: Si no está inscrito, no puede completar lecciones
        if (!$user->courses->contains($course->id)) {
            return redirect()->route('student.course', $course->id)
                             ->with('error', 'Primero debes inscribirte al curso para marcar lecciones como completadas.');
        }
        
        // 1. Guardamos el progreso en la relación Muchos a Muchos
        $user->completedLessons()->syncWithoutDetaching([$lesson->id]);
        
        $course->load('lessons');
        $totalLessons = $course->lessons->count();
        
        // Contamos lecciones terminadas solo de ESTE curso
        $completedCount = $user->completedLessons()
            ->where('course_id', $course->id)
            ->count();
        
        $percentage = ($totalLessons > 0) ? ($completedCount / $totalLessons) * 100 : 0;
        
        // Actualizamos la barra de progreso en la tabla pivote de inscripción
        $user->courses()->updateExistingPivot($course->id, ['progress' => $percentage]);

        // 2. BUSCAR LA SIGUIENTE LECCIÓN PARA EL SALTO AUTOMÁTICO
        $nextLesson = $course->lessons
            ->where('sequence_order', '>', $lesson->sequence_order)
            ->sortBy('sequence_order')
            ->first();

        if ($nextLesson) {
            return redirect()->route('student.course', ['course' => $course->id, 'lesson' => $nextLesson->id])
                             ->with('success', '¡Excelente! Lección completada. Pasando a la siguiente...');
        }

        return redirect()->route('student.course', $course->id)
                         ->with('success', '¡Felicidades! Has completado el contenido del curso.');
    }

    /**
     * Genera la vista del Diploma (Solo al 100% de progreso).
     */
    public function diploma(Course $course)
    {
        $userCourse = Auth::user()->courses()->where('course_id', $course->id)->first();
        $progress = $userCourse ? $userCourse->pivot->progress : 0;

        if ($progress < 100) {
            return redirect()->route('student.course', $course->id)
                             ->with('error', 'Debes completar el 100% del curso para obtener tu certificado.');
        }

        return view('diploma', compact('course'));
    }

    /**
     * Guarda las reseñas.
     */
    public function storeReview(Request $request, Course $course)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10'
        ]);

        DB::table('reviews')->updateOrInsert(
            ['user_id' => Auth::id(), 'course_id' => $course->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return back()->with('success', '¡Gracias por calificar el curso!');
    }
}