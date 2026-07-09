<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterestedCitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_ids' => ['required', 'array', 'min:1'],
            'city_ids.*' => ['integer', 'distinct', 'exists:cities,id'],
        ];
    }
}