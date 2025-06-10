<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * name: opcional, mínimo 3 caracteres si se envía
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|min:3|max:255',
        ];
    }
}
