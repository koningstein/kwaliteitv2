<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('teams', 'name')->ignore($this->team)],
            'users' => ['nullable', 'array'],
            'users.*' => ['exists:users,id'],
            'leaders' => ['nullable', 'array'],
            'leaders.*' => ['exists:users,id'],
        ];
    }
}
