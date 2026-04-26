<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    // Campos permitidos para guardar en la base de datos
    protected $fillable = ['name', 'email', 'specialty'];
}