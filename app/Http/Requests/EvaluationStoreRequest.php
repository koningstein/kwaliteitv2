<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action_point_id' => ['required', 'integer', 'exists:action_points,id'],
            'description'     => ['required', 'string'],
        ];
    }
}
