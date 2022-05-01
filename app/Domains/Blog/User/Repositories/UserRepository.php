<?php

namespace App\Domains\Blog\User\Repositories;

use App\Domains\Blog\User\Entities\User;
use App\Support\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index(): Collection
    {
        return DB::table('users')->select('id', 'name', 'email')->get();
    }

    public function create(array $insert)
    {
        return DB::table("users")->insertGetId($insert);
    }

    public function getUserById(int $id)
    {
        return DB::table('users')->select('*')->where('id', $id)->get();
    }

    public function updateUser(int $id, array $data)
    {
        return DB::table('users')->where('id', $id)->update($data);
    }

    public function deleteUser(int $id)
    {
        return DB::table('users')->delete($id);
    }
}
