<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class HelloWorldController extends Controller
{
    /**
     * Lista todos los ficheros de la carpeta storage/app.
     *
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: Un array con los nombres de los ficheros.
     */
    public function index(): JsonResponse
{
    try {
        $files = Storage::files('');

        return response()->json([
            'mensaje' => 'Listado de ficheros',
            'contenido' => $files
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'mensaje' => 'Error al obtener los archivos: ' . $e->getMessage(),
            'contenido' => []
        ], 500);
    }
}

     /**
     * Recibe por parámetro el nombre de fichero y el contenido. Devuelve un JSON con el resultado de la operación.
     * Si el fichero ya existe, devuelve un 409.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function store(Request $request): JsonResponse
    {
        $filename = $request->input('filename');
        $content = $request->input('content');
    
        if (!$filename || !$content) {
            return response()->json([
                'mensaje' => 'Parámetros incorrectos. El nombre del archivo y contenido son obligatorios.'
            ], 422);
        }
    
        if (Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo ya existe'
            ], 409);
        }
    
        try {
            Storage::put($filename, $content);
            return response()->json([
                'mensaje' => 'Guardado con éxito'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al guardar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
     * Recibe por parámetro el nombre de fichero y devuelve un JSON con su contenido
     *
     * @param name Parámetro con el nombre del fichero.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     * - contenido: El contenido del fichero si se ha leído con éxito.
     */
    public function show(string $filename): JsonResponse
{
    if (!Storage::exists($filename)) {
        return response()->json([
            'mensaje' => 'Archivo no encontrado',
            'contenido' => null
        ], 404);
    }

    try {
        $content = Storage::get($filename);
        return response()->json([
            'mensaje' => 'Archivo leído con éxito',
            'contenido' => $content
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'mensaje' => 'Error al leer el archivo: ' . $e->getMessage(),
            'contenido' => null
        ], 500);
    }
}
    /**
     * Recibe por parámetro el nombre de fichero, el contenido y actualiza el fichero.
     * Devuelve un JSON con el resultado de la operación.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @param content Contenido del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function update(Request $request, string $filename): JsonResponse
    {
        $content = $request->input('content');
    
        if (!$content) {
            return response()->json([
                'mensaje' => 'El contenido del archivo es obligatorio'
            ], 422);
        }
    
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe'
            ], 404);
        }
    
        try {
            Storage::put($filename, $content);
            return response()->json([
                'mensaje' => 'Actualizado con éxito'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recibe por parámetro el nombre de ficher y lo elimina.
     * Si el fichero no existe devuelve un 404.
     *
     * @param filename Parámetro con el nombre del fichero. Devuelve 422 si no hay parámetro.
     * @return JsonResponse La respuesta en formato JSON.
     *
     * El JSON devuelto debe tener las siguientes claves:
     * - mensaje: Un mensaje indicando el resultado de la operación.
     */
    public function destroy(string $filename): JsonResponse
    {
        if (!Storage::exists($filename)) {
            return response()->json([
                'mensaje' => 'El archivo no existe'
            ], 404);
        }
    
        try {
            Storage::delete($filename);
            return response()->json([
                'mensaje' => 'Eliminado con éxito'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }
}
