<?php

namespace App\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;

class IndexCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}
