<?php

namespace App\Models;

class User extends Model
{
    protected $table = "usuarios";

    protected $fillable = [
        'id', 
        'nombre', 
        'apellido', 
        'email', 
        'password',
        'role'
    ];

    protected $hidden = ['password'];

}
