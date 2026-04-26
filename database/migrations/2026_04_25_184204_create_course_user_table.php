<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            
            // Relación con el Usuario (el alumno)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relación con el Curso
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Campo para medir el progreso (de 0 a 100)
            $table->integer('progress')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};