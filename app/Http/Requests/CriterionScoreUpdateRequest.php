<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CriterionScoreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'criterion_id'        => [
                'required',
                'integer',
                'exists:criteria,id',
                Rule::unique('criterion_scores')
                    ->where(fn ($query) => $query->where('reporting_period_id', $this->input('reporting_period_id')))
                    ->ignore($this->route('criterion_score')),
            ],
            'reporting_period_id' => ['required', 'integer', 'exists:reporting_periods,id'],
            'status'              => ['required', 'string', Rule::in(['sufficient', 'attention', 'insufficient'])],
        ];
    }

    public function messages(): array
    {
        return [
            'criterion_id.unique' => 'Er bestaat al een score voor dit criterium in deze rapportageperiode.',
        ];
    }
}
