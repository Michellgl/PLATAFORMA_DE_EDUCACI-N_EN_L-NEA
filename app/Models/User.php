<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Agregamos 'role' y 'avatar' para que funcionen tus nuevas funciones.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * Los atributos que deben permanecer ocultos en las serializaciones (como el API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Cursos en los que el usuario está inscrito
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('progress')->withTimestamps();
    }

    /**
     * Relación: Lecciones que el usuario ha marcado como completadas
     */
    public function completedLessons() 
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user');
    }
}