<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'teacher_id', 'views_count'];

    // Relación: Un curso PERTENECE A un profesor
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Relación: Un curso TIENE MUCHAS lecciones
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sequence_order', 'asc');
    }
    public function users()
{
    return $this->belongsToMany(User::class)->withPivot('progress')->withTimestamps();
}
public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}