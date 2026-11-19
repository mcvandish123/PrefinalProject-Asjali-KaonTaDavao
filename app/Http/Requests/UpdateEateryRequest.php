<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEateryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_open' => $this->boolean('is_open')]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $eatery = $this->route('eatery');

        return [
            'district_id' => ['required', 'exists:districts,id'],
            'name' => ['required', 'string', 'max:100', Rule::unique('eateries', 'name')->ignore($eatery)],
            'address' => ['required', 'string', 'max:255'],
            'price_range' => ['required', 'in:budget,mid,splurge'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'is_open' => ['boolean'],
            'permit_no' => ['required', 'string', 'max:50', Rule::unique('permits', 'permit_no')->ignore($eatery->permit?->id)],
            'expires_on' => ['required', 'date'],
            'features' => ['nullable', 'array'],
            'features.*' => ['exists:features,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'district_id.required' => "Kaon Ta! Davao - Asjali\x27s Food Guide needs a district for every eatery.",
            'name.required' => "Please give the eatery a name for Asjali\x27s Food Guide.",
            'name.unique' => "Asjali\x27s Food Guide already lists an eatery with that name.",
            'price_range.in' => 'Price range must be budget, mid or splurge.',
        ];
    }
}
