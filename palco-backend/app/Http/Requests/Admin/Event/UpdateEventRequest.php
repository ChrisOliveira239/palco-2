<?php

namespace App\Http\Requests\Admin\Event;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->usu_role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:50'],
            'venue_name' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'ticket_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}