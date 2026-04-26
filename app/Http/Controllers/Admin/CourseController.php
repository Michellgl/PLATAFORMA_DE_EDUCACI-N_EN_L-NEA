<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Muestra la lista de cursos con reportes de calificación.
     */
    public function index()
    {
        // Traemos los cursos, contamos las reseñas, sacamos el promedio de estrellas
        // y los ordenamos del mejor calificado al peor (Reporte de los más aceptados)
        $courses = Course::with('teacher')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Muestra el formulario para crear un nuevo curso.
     */
    public function create()
    {
        // Traemos a los profesores para mostrarlos en el menú desplegable
        $teachers = Teacher::all();
        return view('admin.courses.create', compact('teachers'));
    }

    /**
     * Guarda un nuevo curso en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos todos los campos
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'teacher_id' => 'required|exists:teachers,id', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $course = new Course();
        $course->title = $request->title;
        $course->description = $request->description;
        $course->teacher_id = $request->teacher_id;

        // 2. Procesar la imagen de portada
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('course_images', 'public');
            $course->image_path = $path;
        }

        $course->save();

        return redirect()->route('courses.index')->with('success', 'Curso creado con éxito.');
    }

    /**
     * NUEVA FUNCIÓN: Muestra el reporte de comentarios y reseñas de un curso.
     */
    public function reviews(Course $course)
    {
        // Cargamos las reseñas y el nombre del usuario que las escribió
        $course->load(['reviews.user' => function($query) {
            $query->latest();
        }]);
        
        return view('admin.courses.reviews', compact('course'));
    }

    /**
     * Elimina un curso y su imagen asociada.
     */
    public function destroy(Course $course)
    {
        // Borramos la imagen del storage si existe
        if ($course->image_path) {
            Storage::disk('public')->delete($course->image_path);
        }

        // Eliminamos el curso de la BD
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'El curso fue eliminado para siempre.');
    }
}