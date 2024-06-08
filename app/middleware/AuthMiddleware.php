<?php

namespace App\Middleware;

use Leaf\Middleware;

class AuthMiddleware extends Middleware {

    public function call(){

        auth()->autoConnect();
        $user = auth()->user();

        // Validar que el usuario este autorizado
        if (!$user) {
            response()->exit([
                'error' => 'Unauthorized',
                'data' => auth()->errors()
            ], 401);
        }

        // Obtener el rol del usuario y otorgar permisos según el rol
        $rol = $user['role'];

        $allowedRoutes = [
            'admin' => ['users', 'properties', 'blog'],
            'seller' => ['properties'],
            'blogger' => ['blog']
        ];

        if (!array_key_exists($rol, $allowedRoutes)) {
            response()->exit([
                'error' => 'Rol invalido'
            ], 403); // 403 acceso prohibido
        }

        $currentRoute = explode('/', str_replace('/api/', '', request()->getPathInfo()));
        $requestedResource = $currentRoute[0];

        if (!in_array($requestedResource, $allowedRoutes[$rol])) {
            response()->exit([
                'error'=> 'No tienes permiso para acceder a este recurso',
            ], 403);
        }

        //Usuario autorizado continua
        $this->next();
    }

}