<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // 1. Mostrar la lista de profesores
    public function index()
    {
        $teachers = Teacher::all();
        return view('admin.teachers.index', compact('teachers'));
    }

    // 2. Mostrar el formulario para crear un nuevo profesor
    public function create()
    {
        return view('admin.teachers.create');
    }

    // 3. Guardar el nuevo profesor en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'specialty' => 'required|string|max:255',
        ]);

        Teacher::create($request->all());

        return redirect()->route('teachers.index')
                         ->with('success', '¡Profesor registrado exitosamente!');
    }

    // =========================================================
    // AQUÍ ESTÁ LA FUNCIÓN QUE FALTABA (LA QUE CAUSÓ EL ERROR)
    // =========================================================
    
    // 4. Mostrar el formulario para EDITAR un profesor existente
    public function edit($id)
    {
        // Buscamos al profesor por su ID. Si no existe, da error 404 automático
        $teacher = Teacher::findOrFail($id);
        
        // Retornamos la vista pasándole los datos del profesor
        return view('admin.teachers.edit', compact('teacher'));
    }

    // 5. Actualizar los datos del profesor en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // En el correo ignoramos el ID actual para que no marque error si no cambia el email
            'email' => 'required|email|unique:teachers,email,' . $id,
            'specialty' => 'required|string|max:255',
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->update($request->all());

        return redirect()->route('teachers.index')
                         ->with('success', '¡Datos del profesor actualizados correctamente!');
    }

    // 6. Eliminar al profesor
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')
                         ->with('success', 'El profesor ha sido eliminado del directorio.');
    }
}