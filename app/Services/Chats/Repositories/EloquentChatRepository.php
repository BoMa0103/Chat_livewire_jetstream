<?php

namespace App\Services\Chats\Repositories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EloquentChatRepository implements ChatRepository
{
    public function find(int $id): ?Chat
    {
        return Chat::find($id);
    }

    public function createFromArray(array $data): Chat
    {
        return Chat::create($data);
    }

    public function findChatBetweenTwoUsers(int $userIdFirst, int $userIdSecond): ?Chat
    {
        $user = User::find($userIdFirst);

        return $user->chats()
            ->whereHas('users', function ($query) use ($userIdSecond) {
                $query->where('user_id', $userIdSecond);
            })
            ->where('chat_type', Chat::PRIVATE)
            ->first();
    }

    public function getChatsOrderByDesc(int $userId)
    {
        $user = User::find($userId);

        return $user->chats()->orderByDesc('last_time_message')->get();
    }

    public function getChatReceivers(int $chatId, int $senderUserId)
    {
        return Chat::find($chatId)->users()->where('user_id', '!=', $senderUserId);
    }

    public function deleteChat(int $chatId)
    {
        $chat = Chat::find($chatId);

        DB::table('chat_user')->where('chat_id', $chatId)->delete();

        return $chat->delete();
    }

    public function setLangForChat(int $chatId, int $userId, string $langCode): void
    {
        DB::table('chat_user')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->update(['lang' => $langCode]);
    }

    public function getLangForChat(int $chatId, int $userId): ?string
    {
        return DB::table('chat_user')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->first()->lang;
    }
}
