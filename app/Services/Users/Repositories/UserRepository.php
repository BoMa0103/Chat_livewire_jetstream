<?php

namespace App\Services\Users\Repositories;

use App\Models\User;

interface UserRepository
{
    public function find(int $id): ?User;

    public function createFromArray(array $data): User;

    public function getUsersWithoutUserWithId(int $id);

    public function getUsersAreNotInChat(int $chatId);
}
