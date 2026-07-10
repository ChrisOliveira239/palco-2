<?php

namespace App\Http\Requests\Admin\Event;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class RestoreEventRequest extends FormRequest
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