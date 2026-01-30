<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriterionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'standard_id' => ['required', 'exists:standards,id'],
            'number' => ['required', 'integer', 'min:1'],
            'text' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
        ];
    }
}
