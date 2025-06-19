<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends FormRequest
{
    /**
     * Determina si el usuario puede hacer esta solicitud.
     */
    public function authorize()
    {
        // Solo usuarios autenticados pueden crear restaurantes
        return auth()->check();
    }

    /**
     * Reglas de validación para crear un restaurante.
     *
     * name: requerido, mínimo 3 caracteres, máximo 255
     * address: requerido, cadena, máximo 255
     * phone: opcional, cadena, máximo 20
     * description: opcional, texto
     * user_id: requerido, debe existir en la tabla users
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
