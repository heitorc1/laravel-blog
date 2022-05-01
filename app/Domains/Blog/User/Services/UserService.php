<?php

namespace App\Domains\Blog\User\Services;

use App\Domains\Blog\User\Repositories\UserRepository;
use App\Domains\Blog\User\Representations\NewUserRepresentation;
use App\Domains\Blog\User\Representations\UpdateUserRepresentation;
use Illuminate\Support\Collection;

class UserService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): Collection
    {
        return $this->repository->index();
    }

    public function create(NewUserRepresentation $data)
    {
        $id = $this->repository->create($data->toArray());

        return $this->repository->getUserById($id);
    }

    public function getUserById(int $id)
    {
        return $this->repository->getUserById($id);
    }

    public function updateUser(int $id, UpdateUserRepresentation $data)
    {
        $this->repository->updateUser($id, $data->toArray());

        return $this->repository->getUserById($id);
    }

    public function deleteUser(int $id): array
    {
        $this->repository->deleteUser($id);

        return ["message" => "User deleted"];
    }
}
