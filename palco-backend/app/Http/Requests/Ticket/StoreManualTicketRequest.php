<?php

namespace App\Http\Requests\Ticket;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreManualTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->usu_role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [
            'holder_name' => ['required', 'string', 'max:255'],
            'holder_document' => ['nullable', 'string', 'max:20'],
            'holder_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}