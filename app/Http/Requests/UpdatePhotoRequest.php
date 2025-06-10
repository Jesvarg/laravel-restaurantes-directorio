<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * url: opcional, pero debe ser una URL válida si se proporciona
     */
    public function rules()
    {
        return [
            'url' => 'sometimes|required|url',
        ];
    }
}
