<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Validation\ValidationException;

class PhotoController extends controller
{
    public function index()
    {
        $photos = Photo::all();
        return response()->json($photos);
    }

    public function store(StorePhotoRequest $request)
    {
        try {
            $photo = Photo::create($request->validated());
            return response()->json(['success' => true, 'data' => $photo], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $photo = Photo::find($id);

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
