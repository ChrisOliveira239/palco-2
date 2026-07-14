<?php

namespace App\Http\Requests\Admin\EventSession;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->usu_role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'pricing_type' => ['required', 'string', 'in:free,fixed'],
            'price' => ['required_if:pricing_type,fixed', 'nullable', 'numeric', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
