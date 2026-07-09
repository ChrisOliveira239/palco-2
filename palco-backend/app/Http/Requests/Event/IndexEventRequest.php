<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class IndexEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city_ids' => ['required', 'array', 'min:1'],
            'city_ids.*' => ['integer', 'exists:cities,id'],
        ];
    }
}