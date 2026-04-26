<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Los campos que permitimos guardar masivamente
    protected $fillable = [
        'user_id',
        'course_id',
        'rating',
        'comment',
    ];

    // Relación: Una reseña pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una reseña pertenece a un Curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}