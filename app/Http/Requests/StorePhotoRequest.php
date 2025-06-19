<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotoRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * url: requerido si no hay archivo, URL válida
     * photo: requerido si no hay url, debe ser un archivo de imagen
     * imageable_id: requerido, entero
     * imageable_type: requerido, string (ej: App\Models\Restaurant)
     */
    public function rules()
    {
        return [
            'url' => 'required_without:photo|url',
            'photo' => 'required_without:url|image|max:2048', // 2MB máximo
            'imageable_id' => 'required|integer',
            'imageable_type' => 'required|string',
        ];
    }
}
