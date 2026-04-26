@extends('layouts.admin')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0 text-dark">Gestión de Cursos</h2>
        <p class="text-muted mb-0">Crea cursos y administra su contenido</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('courses.create') }}" class="btn btn-primary-custom" style="background-color: #0d6efd; color: white; border-radius: 8px; padding: 10px 20px;">
            <i class="bi bi-plus-lg me-2"></i>Crear Nuevo Curso
        </a>
    </div>
</div>

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="card card-custom h-100 overflow-hidden shadow-sm border-0" style="border-radius: 15px;">
                
                @if($course->image_path)
                    <img src="{{ asset('storage/' . $course->image_path) }}" alt="Portada de {{ $course->title }}" class="card-img-top" style="height: 160px; object-fit: cover; width: 100%;">
                @else
                    <div class="bg-dark text-white d-flex justify-content-center align-items-center card-img-top" style="height: 160px; background: linear-gradient(45deg, #2c3e50, #4361ee);">
                        <i class="bi bi-play-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h5 class="card-title fw-bold text-truncate" title="{{ $course->title }}">{{ $course->title }}</h5>
                    <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $course->description }}
                    </p>

                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="text-warning me-2">
                            <i class="bi bi-star-fill"></i>
                            <span class="fw-bold text-dark">{{ number_format($course->reviews_avg_rating ?? 0, 1) }}</span>
                        </div>
                        <span class="text-muted small">({{ $course->reviews_count ?? 0 }} reseñas)</span>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="small fw-medium">{{ $course->teacher ? $course->teacher->name : 'Sin asignar' }}</span>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                        <i class="bi bi-eye me-1"></i>{{ $course->views_count }} vistas
                    </span>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.reviews', $course->id) }}" class="btn btn-sm btn-outline-info rounded-pill" title="Leer Comentarios">
                            <i class="bi bi-chat-dots"></i>
                        </a>

                        <a href="{{ route('courses.lessons.index', $course->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Gestionar Videos">
                            <i class="bi bi-camera-video"></i>
                        </a>
                        
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso? Se borrarán todos sus videos y el progreso de los alumnos. Esta acción NO se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Eliminar Curso">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x text-secondary opacity-25" style="font-size: 4rem;"></i>
            <h5 class="mt-3">No hay cursos creados</h5>
            <p class="text-muted">Inicia creando el primer curso para tu plataforma.</p>
        </div>
    @endforelse
</div>
@endsection