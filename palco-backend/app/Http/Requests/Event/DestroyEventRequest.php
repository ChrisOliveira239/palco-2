<?php

namespace App\Http\Requests\Event;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class DestroyEventRequest extends FormRequest
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
