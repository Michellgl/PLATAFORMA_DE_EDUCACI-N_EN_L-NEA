@extends('layouts.admin')

@section('content')
<div class="row mt-4 justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="fw-bold mb-0"><i class="bi bi-journal-plus text-primary me-2"></i>Crear Nuevo Curso</h4>
            </div>
            <div class="card-body p-4">
                
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Título del Curso</label>
                        <input type="text" name="title" class="form-control form-control-lg bg-light border-0" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Profesor Asignado</label>
                        <select name="teacher_id" class="form-select form-select-lg bg-light border-0" required>
                            <option value="">Selecciona un instructor...</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->specialty }})</option>
                            @endforeach
                        </select>
                        @if($teachers->isEmpty())
                            <small class="text-danger mt-1 d-block"><i class="bi bi-exclamation-triangle"></i> Debes registrar al menos un profesor primero.</small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Descripción del Curso</label>
                        <textarea name="description" class="form-control bg-light border-0" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Portada del Curso (Opcional)</label>
                        <input type="file" name="image" accept="image/*" class="form-control form-control-lg bg-light border-0">
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle"></i> Sube una imagen en formato JPG o PNG para que se vea en el catálogo de alumnos. (Máx. 2MB).
                        </small>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="{{ route('courses.index') }}" class="btn btn-light px-4" style="border-radius: 8px;">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-5" style="background-color: #0d6efd; border-radius: 8px;">Guardar Curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection