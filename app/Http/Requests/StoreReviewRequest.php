<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * rating: requerido, entero entre 1 y 5
     * comment: opcional, texto
     * user_id: requerido, debe existir
     * restaurant_id: requerido, debe existir
     */
    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'restaurant_id' => 'required|exists:restaurants,id',
        ];
    }
}
