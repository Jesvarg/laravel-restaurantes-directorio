<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Subir foto
     */
    public function store(StorePhotoRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Si se envía un archivo
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $path = $file->store('photos', 'public');
                $data['url'] = asset('storage/' . $path);
                unset($data['photo']); // Remover el archivo del array
            }
            
            $photo = Photo::create($data);
            
            return response()->json([
                'success' => true, 
                'photo' => $photo,
                'url' => $photo->url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => 'Error al subir la foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar foto
     */
    public function destroy(Photo $photo)
    {
        try {
            // Eliminar archivo del storage si existe
            if (str_contains($photo->url, 'storage/')) {
                $path = str_replace(asset('storage/'), '', $photo->url);
                Storage::disk('public')->delete($path);
            }
            
            // Eliminar registro de la base de datos
            $photo->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'error' => 'Error al eliminar la foto: ' . $e->getMessage()
            ], 500);
        }
    }
}