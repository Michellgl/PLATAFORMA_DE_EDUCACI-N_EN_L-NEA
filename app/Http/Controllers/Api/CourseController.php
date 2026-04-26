<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Método para listar todos los cursos
    public function index()
    {
        // Obtenemos todos los cursos de la base de datos
        $courses = Course::all();
        
        // Devolvemos la respuesta en formato JSON
        return response()->json([
            'success' => true,
            'message' => 'Lista de cursos obtenida correctamente',
            'data' => $courses
        ], 200);
    }
}