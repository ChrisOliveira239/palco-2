<?php

namespace App\Http\Requests\Admin\Event;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class IndexEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->usu_role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'status' => ['nullable', 'string', 'in:active,inactive,all'],
        ];
    }
}