<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StandardStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme_id' => ['required', 'exists:themes,id'],
            'code' => ['required', 'string', 'max:20', 'unique:standards,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
