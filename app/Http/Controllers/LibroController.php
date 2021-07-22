<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

use Carbon\Carbon;

class LibroController extends Controller{

    public function index(){

        $datosLibro = Libro::all();

        return response()->json($datosLibro);
    }

    public function guardar(Request $request){
        // return response()->json("Hola dev-guerra");

        // MOSTRAR SOLO ESE CAMPO
        // return response()->json($request->input('titulo'));

        // MOSTRAR TODOS LOS CAMPOS INGRESADOS
        // return response()->json($request);

        // GUARDAR 'string'
        // $datosLibro = new Libro;
        // $datosLibro->titulo = $request->titulo;
        // $datosLibro->imagen = $request->imagen;
        // $datosLibro->save();
        // return response()->json($request);

        // GUADAR - FILE IMAGEN
        $datosLibro = new Libro;

        if($request->hasFile('imagen')){
            $nombreArchivoOriginal=$request->file('imagen')->getClientOriginalName();

            $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchivoOriginal;

            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);

            $datosLibro->titulo = $request->titulo;
            $datosLibro->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
            $datosLibro->save();

        }
        return response()->json($nuevoNombre);
    }

    public function ver($id){
        $datosLibro = new Libro;
        $datosEncontrados= $datosLibro->find($id);

        return response()->json($datosEncontrados);
    }

    public function eliminar($id){

        $datosLibro = Libro::find($id);

        if($datosLibro){
            $rutaArchivo=base_path('public').$datosLibro->imagen;

            if(file_exists($rutaArchivo)){
                unlink($rutaArchivo);
            }
            $datosLibro->delete();
        }
        return response()->json("Registro borrado");
    }

    public function actualizar(Request $request, $id){

        $datosLibro = Libro::find($id);

        // verificando si hay una nueva imagen ingresada
        if($request->hasFile('imagen')){

            // primero elimino el anterior
            if($datosLibro){
                $rutaArchivo=base_path('public').$datosLibro->imagen;
    
                if(file_exists($rutaArchivo)){
                    unlink($rutaArchivo);
                }
                $datosLibro->delete();
            }

            // segundo guardo la nueva imagen
            $nombreArchivoOriginal=$request->file('imagen')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp."_".$nombreArchivoOriginal;
            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino, $nuevoNombre);
            $datosLibro->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
            $datosLibro->save();
        }

        // verificando si hay un nuevo titulo ingresado
        if($request->input('titulo')){
            $datosLibro->titulo=$request->input('titulo');
        }
        $datosLibro->save();

        return response()->json("Datos Actualizados");
    }

}