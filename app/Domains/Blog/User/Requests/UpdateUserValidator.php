<?php

namespace App\Domains\Blog\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserValidator extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'email' => 'nullable|string',
            'password' => 'nullable|string',
        ];
    }
}
