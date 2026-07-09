<?php

namespace App\Http\Requests\Ticket;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class IndexTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->usu_role === UserRole::Admin;
    }

    public function rules(): array
    {
        return [];
    }
}
