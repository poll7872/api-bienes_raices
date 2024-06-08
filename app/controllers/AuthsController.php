<?php

namespace App\Controllers;
use Leaf\Auth;


class AuthsController extends Controller
{
    public function login()
    {
        $auth = new Auth;
        $auth->autoConnect();

        $data = $auth->login([
            "email"=> request()->get("email"),
            "password"=> request()->get("password"),
        ]);

        if ($data) {
            //Si es autenticado crar una sesión token
            $token = $data["token"];
            $user = $data["user"];
            return response()->json([
                "message" => "Usuario autenticado con exito",
                "token"=> $token,
                "user"=> $user
            ]);
        } else {
            //Si NO esta autenticado mostrar error
            $errors = $auth->errors();
            return response()->json([
                'errors' => $errors,
                "message"=> "Usuario no autenticado",
            ]);
        }
    }
}
