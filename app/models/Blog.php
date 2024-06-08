<?php

namespace App\Models;

class Blog extends Model
{
    protected $table = "blog";
    protected $fillable = [
        'titulo',
        'contenido',
        'usuarios_id',
    ];
}
