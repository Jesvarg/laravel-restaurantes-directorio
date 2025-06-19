<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * rating: opcional, entero entre 1 y 5
     * comment: opcional, texto
     */
    public function rules()
    {
        return [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }
}
