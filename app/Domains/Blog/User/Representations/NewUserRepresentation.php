<?php

namespace App\Domains\Blog\User\Representations;

use Illuminate\Support\Facades\Hash;

class NewUserRepresentation
{
    private $name;
    private $email;
    private $password;
    private $nowDate;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = Hash::make($data['password']);
        $this->nowDate = date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'created_at' => $this->nowDate,
            'updated_at' => $this->nowDate,
        ];
    }
}
