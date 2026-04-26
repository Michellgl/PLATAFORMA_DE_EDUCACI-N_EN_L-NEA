<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    // Cambiamos 'video_url' por 'video_path'
    protected $fillable = ['course_id', 'title', 'video_path', 'sequence_order'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}