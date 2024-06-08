<?php

namespace App\Models;

class Property extends Model
{
    protected $table = 'propiedades';
    protected $fillable = [
        'id',
        'titulo',
        'precio',
        'imagen',
        'descripcion',
        'habitaciones',
        'wc',
        'estacionamiento',
        'usuarios_id'
    ];
}
