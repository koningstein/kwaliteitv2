<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndicatorStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'criterion_id' => ['required', 'exists:criteria,id'],
            'text' => ['required', 'string'],
        ];
    }
}
