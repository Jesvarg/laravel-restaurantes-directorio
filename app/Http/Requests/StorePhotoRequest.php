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
     * url: requerido, URL válida
     * imageable_id: requerido, entero
     * imageable_type: requerido, string (ej: App\Models\Restaurant)
     */
    public function rules()
    {
        return [
            'url' => 'required|url',
            'imageable_id' => 'required|integer',
            'imageable_type' => 'required|string',
        ];
    }
}
