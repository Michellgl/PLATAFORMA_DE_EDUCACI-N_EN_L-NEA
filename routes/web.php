<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Importamos los controladores del panel administrativo
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;

// Importamos el controlador del Estudiante
use App\Http\Controllers\StudentCourseController;

// Importamos los modelos necesarios
use App\Models\User;
use Illuminate\Support\Facades\Hash;


// 1. Ruta de bienvenida (Redirige al login)
Route::get('/', function () {
    return redirect('/login');
});

// =========================================================
// 🎓 ZONA DEL ESTUDIANTE (Catálogo e Inscripciones)
// =========================================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // El catálogo de cursos (Dashboard)
    
    // Proceso de inscripción (POST)
    Route::post('/curso/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    
    // Sala de reproducción (Acceso abierto con sistema anti-trampas)
    Route::get('/curso/{course}', [StudentCourseController::class, 'show'])->name('student.course');

    // Marcar lección como completada (Actualiza progreso)
    Route::post('/lesson/{lesson}/complete', [StudentCourseController::class, 'completeLesson'])->name('lessons.complete');

    // Guardar reseña y comentario (Obligatorio para el diploma)
    Route::post('/curso/{course}/review', [StudentCourseController::class, 'storeReview'])->name('courses.review');

    // Generar el diploma (Protegido por progreso y reseña)
    Route::get('/curso/{course}/diploma', [StudentCourseController::class, 'diploma'])->name('student.diploma');
});

// =========================================================
// 🔒 ZONA PRIVADA: PANEL DE ADMINISTRACIÓN
// =========================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // Inicio del panel (Redirige a Profesores o Dashboard)
    Route::get('/', [TeacherController::class, 'index'])->name('admin.home');
    
    // Gestión de Profesores
    Route::resource('teachers', TeacherController::class)->except(['index']);
    Route::get('teachers', [TeacherController::class, 'index'])->name('teachers.index');
    
    // Gestión de Cursos
    Route::resource('courses', CourseController::class);

    // NUEVA: Reporte de Calificaciones y Comentarios por curso
    Route::get('courses/{course}/reviews', [CourseController::class, 'reviews'])->name('courses.reviews');

    // Gestión de Lecciones (Videos)
    Route::resource('courses.lessons', LessonController::class)->shallow();
});

// =========================================================
// 🚪 RUTA TEMPORAL: CREACIÓN DEL ADMINISTRADOR MAESTRO
// =========================================================
Route::get('/crear-admin-secreto', function () {
    $email = 'admin@educademy.com';
    if (User::where('email', $email)->exists()) {
        return "El administrador con el correo $email ya existe en la base de datos.";
    }

    $admin = new User();
    $admin->name = 'Administrador Principal';
    $admin->email = $email;
    $admin->password = Hash::make('admin6969');
    $admin->role = 'admin'; 
    $admin->save();

    return "¡Éxito! Usuario '$email' creado. Usa la contraseña 'admin6969'.";
});

Route::delete('/admin/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
// =========================================================
// RUTAS DE PERFIL (Laravel Breeze)
// =========================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';