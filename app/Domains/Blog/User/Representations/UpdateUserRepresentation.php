<?php

namespace App\Domains\Blog\User\Representations;

use Illuminate\Support\Facades\Hash;

class UpdateUserRepresentation
{
    private $name;
    private $email;
    private $password;
    private $nowDate;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;

        if(!is_null($this->password))
        {
            $this->password = Hash::make($data['password']);
        } else {
            $this->password = null;
        }
        $this->nowDate = date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        $data = [];

        if(!is_null($this->name))
        {
            $data['name'] = $this->name;
        }

        if(!is_null($this->email))
        {
            $data['email'] = $this->email;
        }

        if(!is_null($this->password))
        {
            $data['password'] = $this->password;
        }

        $data['updated_at'] = $this->nowDate;

        return $data;
    }
}
