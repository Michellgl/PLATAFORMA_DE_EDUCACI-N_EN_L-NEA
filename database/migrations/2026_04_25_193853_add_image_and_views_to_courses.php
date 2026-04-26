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
        Schema::table('courses', function (Blueprint $table) {
            // Revisa si falta la columna de la imagen y la agrega
            if (!Schema::hasColumn('courses', 'image_path')) {
                $table->string('image_path')->nullable()->after('description');
            }
            
            // Revisa si falta la columna de visitas (ya sabemos que la tienes, así que se la saltará)
            if (!Schema::hasColumn('courses', 'views_count')) {
                $table->integer('views_count')->default(0)->after('teacher_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};