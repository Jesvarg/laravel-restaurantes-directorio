<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * name: requerido, cadena, mínimo 3 caracteres, máximo 255
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
        ];
    }
}
