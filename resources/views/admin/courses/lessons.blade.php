@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <a href="{{ route('courses.index') }}" class="btn btn-light rounded-circle me-3 shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">Contenido del Curso</h2>
                    <p class="text-primary mb-0 fw-semibold">{{ $course->title }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            {{-- Este botón debería abrir un modal o ir a otra vista para subir video --}}
            <a href="{{ route('lessons.create', ['course_id' => $course->id]) }}" class="btn btn-outline-secondary border-0 fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Añadir Video
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary border-0">Secuencia</th>
                            <th class="py-3 text-secondary border-0">Título del Video</th>
                            <th class="py-3 text-secondary border-0 text-center">Vista Previa</th>
                            <th class="py-3 text-secondary border-0 text-end px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->lessons->sortBy('sequence_order') as $lesson)
                            <tr>
                                <td class="px-4">
                                    <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">
                                        {{ $lesson->sequence_order }}
                                    </div>
                                </td>
                                <td class="fw-medium">
                                    <i class="bi bi-play-circle-fill text-primary me-2"></i>
                                    {{ $lesson->title }}
                                </td>
                                <td class="text-center" style="width: 250px;">
                                    <div class="rounded-3 overflow-hidden shadow-sm bg-black" style="height: 100px;">
                                        <video class="w-100 h-100" preload="metadata">
                                            <source src="/storage/{{ $lesson->video_path }}" type="video/mp4">
                                        </video>
                                    </div>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- FORMULARIO DE ELIMINAR --}}
                                        <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este video permanentemente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger rounded-3 p-2">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-camera-video opacity-25" style="font-size: 3rem;"></i>
                                    <p class="mt-2">No hay videos cargados en este curso.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .card { border-radius: 1rem; }
    .table thead th { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endsection