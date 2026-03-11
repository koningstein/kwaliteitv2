<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionPointStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'criterion_id'           => ['required', 'integer', 'exists:criteria,id'],
            'team_id'                => ['required', 'integer', 'exists:teams,id'],
            'action_point_status_id' => ['required', 'integer', 'exists:action_point_statuses,id'],
            'user_id'                => ['nullable', 'integer', 'exists:users,id'],
            'description'            => ['required', 'string'],
            'start_date'             => ['required', 'date'],
            'end_date'               => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
