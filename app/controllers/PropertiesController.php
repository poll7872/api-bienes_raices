<?php

namespace App\Controllers;
use App\Models\Property;
use App\Models\User;

class PropertiesController extends Controller
{
    public function index()
    {
        try {
            $properties = Property::all();
            return response()->json($properties);
        } catch (\Exception $e) {
            return response()->json([
                "error"=> $e->getMessage()
            ], 500);
        }
    }

    public function store() {

        try {
            // 1. Validar los datos del request
            $validation = request()->validate([
                'titulo' => 'required|text|max:40',
                'precio' => 'required|number',
                'imagen' => 'required|text|max:80',
                'descripcion' => 'required|text',
                'habitaciones' => 'required|number',
                'wc' => 'required|number',
                'estacionamiento' => 'required|number',
                'usuarios_id' => 'required|number'
            ]);

            //2. Si no pasa la validación que nos muestre el siguiente response
            if (!$validation) {
                $errors = request()->errors();
                return response()->json([
                    'status' => 'error',
                    'message'=> 'Datos de entrada invalidos.',
                    'errors' => $errors
                ], 400);
            }

            //3. Validar que el usuario a registrar su role sea de vendedor
            $user = User::find($validation['usuarios_id']);
            if (!$user || $user->role !== 'seller') {
                return response()->json([
                    'status' => 'error',
                    'message'=> 'El usuario no es un vendedor.'
                ], 400);
            }

            //4. Crear la propiedad
            $property = Property::create([
                'titulo' => $validation['titulo'],
                'precio' => $validation['precio'],
                'imagen' => $validation['imagen'],
                'descripcion' => $validation['descripcion'],
                'habitaciones' => $validation['habitaciones'],
                'wc' => $validation['wc'],
                'estacionamiento' => $validation['estacionamiento'],
                'usuarios_id' => $validation['usuarios_id']
            ]);

            //5. Retornar la info del usuario creado
            return response()->json([
                'success' => true,
                'message' => 'Propiedad creada correctamente',
                'property' => $property
            ], 201);


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }

    }

    public function show($id){

        //1. Validar si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID invalido'
            ], 400);
        }

        //2. Buscar el usuario por el id
        $property = Property::find($id);

        //3. Validar que la propiedad exista
        if (!$property) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Propiedad no encontrada'
            ]);
        }

        //4. Retornar la info de la propiedad buscada
        return response()->json([
            'status' => 'success',
            'data' => $property
        ], 200);
    }

    public function update($id) {
        
        //1. Validar si el id es númerico
        if (!is_numeric($id)) {
            return response ()->json([
                'status'=> 'error',
                'message' => 'ID invalido'
            ]);
        }

        //2. Validar los datos del request
        $validation = request()->validate([
            'titulo' => 'required|text|max:40',
            'precio' => 'required|number',
            'imagen' => 'required|text|max:80',
            'descripcion' => 'required|text',
            'habitaciones' => 'required|number',
            'wc' => 'required|number',
            'estacionamiento' => 'required|number',
            'usuarios_id' => 'required|number'
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

        //4. Buscar la propiedad por el id
        $property = Property::find($id);

        //5. Validar si la propiedad existe
        if (!$property) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Propiedad no encontrada'
            ]);
        }

        //6. Validar que el usuario a actualizar su role sea de vendedor
        $user = User::find($validation['usuarios_id']);
        if (!$user || $user->role !== 'seller') {
            return response()->json([
                'status' => 'error',
                'message'=> 'El usuario no es un vendedor.'
            ], 400);
        }
        
        //7. Actualizar los datos de la propiedad
        $property->titulo = request()->get('titulo');
        $property->precio = request()->get('precio');
        $property->imagen = request()->get('imagen');
        $property->descripcion = request()->get('descripcion');
        $property->habitaciones = request()->get('habitaciones');
        $property->wc = request()->get('wc');
        $property->estacionamiento = request()->get('estacionamiento');
        $property->usuarios_id = request()->get('usuarios_id');
        $property->save();

        //8. Retornar la info actualizada de la propiedad
        return response()->json([
            'status'=> 'success',
            'message'=> 'Propiedad actualizada correctamente',
            'data' => $property
        ], 200);
    }

    public function destroy($id) {

        //1. Validar si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'ID invalido'
            ], 400);
        }

        //2. Buscar la propiedad por el id
        $property = Property::find($id);

        //3. Validar si el usuario existe
        if (!$property) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Propiedad no encontrada'
            ]);
        }

        //4. Eliminar usuario
        $property->delete();

        return response()->json([
            'status'=> 'success',
            'message'=> 'Propiedad eliminada correctamente'
        ]);

    }
}
