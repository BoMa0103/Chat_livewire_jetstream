<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\Users\Repositories\UserRepository;

class UsersService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createFromArray(array $data): User
    {
        return $this->userRepository->createFromArray($data);
    }

    public function getUsersWithoutUserWithId(int $id)
    {
        return $this->userRepository->getUsersWithoutUserWithId($id);
    }

    public function getUsersAreNotInChat(int $chatId)
    {
        return $this->userRepository->getUsersAreNotInChat($chatId);
    }
}
