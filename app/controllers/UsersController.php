<?php

namespace App\Controllers;
use App\Models\User;
use Leaf\Helpers\Password;

class UsersController extends Controller
{
    public function index()
    {
       try {

          $users = User::all();
          return response()->json($users);

       } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 500]);
       }
    }

    public function store() {

        try {
            // 1. Validar los datos del request
            $validation = request()->validate([
                'nombre' => 'required|text|max:80',
                'apellido' => 'required|text|max:80',
                'email' => 'required|email|max:80',
                'password' => 'required|text|max:30',
                'role' => 'required|text'
            ]);

            // 2. Si no pasa la validación nos muestra el siguiente response
            if (!$validation) {
                $errors = request()->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Datos de entrada invalidos.',
                    'errors'=> $errors
                ], 400);
            }

            // 3. Hashear la contraseña con BCRYPT
            $hash = Password::hash($validation['password'], Password::BCRYPT);

            // 4. Crear el usuario
            $user = User::create([
                'nombre' => $validation['nombre'],
                'apellido' => $validation['apellido'],
                'email' => $validation['email'],
                'password'=> $hash,
                'role' => $validation['role']
            ]);

            // 5. retornar la info del usuario creado
            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente.',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }

    }

    public function show($id) {
        
        // 1. Valida si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID invalido'
            ], 400);
        }
        
        // 2. Buscar el usuario por el id
        $user = User::find($id);

        // 3. Validar que el usuario exista
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'usuario no encontrado.'
            ]);
        }

        //4. Retornar la info del usuario buscado
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function update($id) {
        
        // 1. Valida si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID invalido'
            ], 400);
        }
        
        // 2. Valida los datos del request
        $validation = request()->validate([
            'nombre' => 'required|text|max:80',
            'apellido' => 'required|text|max:80',
            'email' => 'required|email|max:80',
            'role' => 'required|text'
        ]);

        // 3. Si no pasa la validación nos muestra el siguiente response
        if (!$validation) {
            $errors = request()->errors();
            return response()->json([
                'status' => 'error',
                'message' => 'Datos de entrada invalidos.',
                'errors'=> $errors
            ], 400);
        }

        // 4. Busca el usuario por el id
        $user = User::find($id);

        // 5. Valida si el usuario existe
        if (!$user) {
            return response()->json([
                'status'=> 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }

        // 6. Actualizar los datos de usuario
        $user->nombre = request()->get('nombre');
        $user->apellido = request()->get('apellido');
        $user->email = request()->get('email');
        $user->role = request()->get('role');
        $user->save();

        // 7. Devolver info actualizada del usuario
        return response()->json([
            'status'=> 'success',
            'message' => 'Usuario actualizado correctamente',
            'data' => $user
        ], 200);
        
    }

    public function destroy($id) {

        // 1. Valida si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID invalido'
            ], 400);
        }

        // 2. Busca el usuario por el id
        $user = User::find($id);

        // 3. Valida si el usuario existe
        if (!$user) {
            return response()->json([
                'status'=> 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }

        // 4. Eliminar usuario
        $user->delete();

        return response()->json([
            'status'=> 'success',
            'message' => 'Usuario eliminado correctamente.'
        ], 200);

    }


}
