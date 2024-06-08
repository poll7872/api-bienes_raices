<?php

namespace App\Controllers;
use App\Models\Blog;
use App\Models\User;
use Leaf\Http\Response;

class BlogsController extends Controller
{
    public function index()
    {
        try {
            $blogs = Blog::all();
            return response()->json($blogs);
        } catch (\Exception $e) {
            return response()->json([
                "error"=> $e->getMessage(),
            ], 500);
        }
    }

    public function store() {

        try {
            // 1. Validar los datos del request
            $validation = request()->validate([
                'titulo' => 'required|text|max:40',
                'contenido'=> 'required',
                'usuarios_id'=> 'required|number',
            ]);

            // 2. Si no pasa la validación que no muestre un error
            if (!$validation) {
                $errors = request()->errors();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Datos de entrada invalidos',
                    'errors'=> $errors
                ]);
            }

            // 3. Validar que el usuario a registrar su role sea bloguero
            $user = User::find($validation['usuarios_id']);
            if (!$user || $user->role !== 'blogger') {
                return response()->json([
                    'status' => 'error',
                    'message'=> 'El usuario no es blogero'
                ], 400);
            }

            // 4. Crear la entrada de blog
            $blog = Blog::create([
                'titulo'=> $validation['titulo'],
                'contenido'=> $validation['contenido'],
                'usuarios_id'=> $validation['usuarios_id'],
            ]);

            // 5. Retornar la info del usuario creado
            return response()->json([
                'status'=> 'success',
                'message'=> 'Entrada de blog creado correctamente.',
                'blog' => $blog
            ], 201);

        } catch (\Exception $e) {       
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }

    }

    public function show($id) {

        // 1. Validar que el id sea númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'ID invalido'
            ], 400);
        }

        // 2. Busca el usuario por el id
        $blog = Blog::find($id);

        // 3. Validar que el blog exista
        if (!$blog) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Blog no encontrado'
            ]);
        }

        // 4. Retornar la info del blog buscado
        return response()->json([
            'status'=> 'success',
            'data' => $blog
        ]);

    }

    public function update($id){

        //1. Validar si el id es númerico
        if (!is_numeric( $id )) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'ID invalido'
            ]);
        }

        //2. Validar los datos del request
        $validation = request()->validate([
            'titulo' => 'required|text|max:40',
            'contenido'=> 'required',
            'usuarios_id'=> 'required|number',
        ]);

        //3. Si no pasa la validación nos muestra el siguiente response
        if (!$validation) {
            $errors = request()->errors();
            return response()->json([
                'status'=> 'error',
                'message' => 'Datos de entrada invalidos',
                'errors'=> $errors
            ], 400);
        }

        //4. Buscar si el blog existe
        $blog = Blog::find($id);

        //5. Validar si el blog existe
        if (!$blog) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Blog no encontrado'
            ]);
        }

        //6. Validar que el usuario a actualizar su id sea blogger
        $user = User::find($validation['usuarios_id']);
        if (!$user || $user->role !== 'blogger') {
            return response()->json([
                'status' => 'error',
                'message'=> 'El usuario no es blogero'
            ], 400);
        }

        //7. Actualizar los datos del blog
        $blog->titulo = request()->get('titulo');
        $blog->contenido = request()->get('contenido');
        $blog->usuarios_id = request()->get('usuarios_id');
        $blog->save();

        //8. Retornar la info actualizada del blog
        return Response()->json([
            'status' => 'success',
            'message'=> 'Blog actualizado correctamente',
            'data' => $blog
        ], 200);

    }

    public function destroy($id) {

        //1. Validar si el id es númerico
        if (!is_numeric($id)) {
            return response()->json([
                'status' => 'error',
                'message'=> 'ID invalido'
            ], 400);
        }

        //2. Buscar la propiedad por el id
        $blog = Blog::find($id);

        //3. Validar si el blog existe
        if (!$blog) {
            return response()->json([
                'status'=> 'error',
                'message'=> 'Blog no encontrado'
            ]);
        }

        //4. Eliminar usuario
        $blog->delete();

        return response()->json([
            'status'=> 'success',
            'message'=> 'Blog eliminado correctamente'
        ]);

    }
}
