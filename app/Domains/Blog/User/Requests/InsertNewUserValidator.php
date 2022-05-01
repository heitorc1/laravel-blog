<?php

namespace App\Domains\Blog\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertNewUserValidator extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
