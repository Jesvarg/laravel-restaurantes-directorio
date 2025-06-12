<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Validation\ValidationException;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::all();
        return response()->json($photos);
    }

    public function store(StorePhotoRequest $request)
    {
        try {
            // Si se envía un archivo
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $path = $file->store('photos', 'public');
                $url = asset('storage/' . $path);
                
                // Crear la foto con la URL generada
                $data = $request->validated();
                $data['url'] = $url;
                
                $photo = Photo::create($data);
            } else {
                // Si solo se envía una URL
                $photo = Photo::create($request->validated());
            }
            
            // Cargar la relación polimórfica
            $photo->load('imageable');
            
            return response()->json(['success' => true, 'data' => $photo], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $photo = Photo::with('imageable')->find($id);

        if (!$photo) {
            return response()->json(['message' => 'Foto no encontrada'], 404);
        }

        return response()->json($photo);
    }

    public function update(UpdatePhotoRequest $request, $id)
    {
        $photo = Photo::find($id);

        if (!$photo) {
            return response()->json(['message' => 'Foto no encontrada'], 404);
        }

        try {
            $photo->update($request->validated());
            return response()->json(['success' => true, 'data' => $photo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);

        if (!$photo) {
            return response()->json(['message' => 'Foto no encontrada'], 404);
        }

        try {
            $photo->delete();
            return response()->json(['message' => 'Foto eliminada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
