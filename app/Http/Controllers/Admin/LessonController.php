<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    // Mostrar los videos de un curso específico
    public function index(Course $course)
    {
        // Trae las lecciones relacionadas al curso
        $lessons = $course->lessons()->orderBy('sequence_order', 'asc')->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    // Mostrar formulario para agregar un video
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    // Guardar el video en la base de datos y el archivo físico
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sequence_order' => 'required|integer|min:1',
            // Límite de ~200MB (204800 KB) - Ajustado para estabilidad en Hostinger
            'video_file' => 'required|mimes:mp4,mov,ogg,qt|max:204800'
        ]);

        // 1. Recibir el archivo
        $file = $request->file('video_file');
        
        // 2. Guardarlo en storage/app/public/videos
        $path = $file->store('videos', 'public');

        // 3. Crear el registro vinculado al curso
        $course->lessons()->create([
            'title' => $request->title,
            'sequence_order' => $request->sequence_order,
            'video_path' => $path 
        ]);

        // CORRECCIÓN: Se cambió a courses.lessons.index para que coincida con tu web.php
        return redirect()->route('courses.lessons.index', $course->id)
                         ->with('success', 'Video subido y procesado correctamente.');
    }

    // ============================================================
    // ESTA ES LA FUNCIÓN QUE TE FALTABA PARA QUE EL BOTÓN SIRVA
    // ============================================================
    public function destroy(Lesson $lesson)
    {
        // 1. Borrar el archivo físico del servidor para liberar espacio en Hostinger
        if ($lesson->video_path && Storage::disk('public')->exists($lesson->video_path)) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        // 2. Eliminar el registro de la base de datos
        $lesson->delete();

        return back()->with('success', 'El video ha sido eliminado permanentemente.');
    }
}