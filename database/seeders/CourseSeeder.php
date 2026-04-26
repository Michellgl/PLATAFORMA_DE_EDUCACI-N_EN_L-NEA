<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'title' => 'Curso de Flutter para Principiantes',
            'description' => 'Aprende a crear apps móviles',
            'teacher_id' => 1
        ]);
    }
}