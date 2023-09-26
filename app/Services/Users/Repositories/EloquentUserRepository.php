<?php

namespace App\Services\Users\Repositories;

use App\Models\Chat;
use App\Models\User;

class EloquentUserRepository implements UserRepository
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function createFromArray(array $data): User
    {
        return User::create($data);
    }

    public function getUsersWithoutUserWithId(int $id)
    {
        return User::where('id', '!=', $id)->get();
    }
}
