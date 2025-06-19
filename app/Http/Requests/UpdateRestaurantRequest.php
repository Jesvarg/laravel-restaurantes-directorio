<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Reglas de validación para actualizar un restaurante.
     *
     * name: opcional, mínimo 3 caracteres si se envía
     * address: opcional, cadena, máximo 255
     * phone: opcional, cadena, máximo 20
     * description: opcional, texto
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|min:3|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ];
    }
}
