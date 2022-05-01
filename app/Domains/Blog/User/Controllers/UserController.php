<?php

namespace App\Domains\Blog\User\Controllers;

use App\Domains\Blog\User\Representations\NewUserRepresentation;
use App\Domains\Blog\User\Representations\UpdateUserRepresentation;
use App\Domains\Blog\User\Requests\InsertNewUserValidator;
use App\Domains\Blog\User\Requests\UpdateUserValidator;
use App\Domains\Blog\User\Resources\UserResource;
use App\Domains\Blog\User\Services\UserService;
use App\Support\Http\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->service->index();
        return UserResource::collection($users);
    }

    public function create(InsertNewUserValidator $request)
    {
        $request->validated();

        $representedData = new NewUserRepresentation($request->all());

        return UserResource::collection($this->service->create($representedData));
    }

    public function getUserById(int $id)
    {
        return UserResource::collection($this->service->getUserById($id));
    }

    public function updateUser(int $id, UpdateUserValidator $request)
    {
        $request->validated();

        $representedData = new UpdateUserRepresentation($request->all());

        return UserResource::collection($this->service->updateUser($id, $representedData));
    }

    public function deleteUser(int $id)
    {
        return $this->service->deleteUser($id);
    }
}
