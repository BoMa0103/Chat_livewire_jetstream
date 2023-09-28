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

    public function getUsersAreNotInChat(int $chatId)
    {
        $chat = Chat::find($chatId);

        $usersInChat = $chat->users()->pluck('user_id');

        return User::whereNotIn('id', $usersInChat)->get();
    }
}
