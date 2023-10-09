<?php

namespace App\Services\Messages\Repositories;

use App\Models\Message;

class EloquentMessageRepository implements MessageRepository
{
    public function find(int $id): ?Message
    {
        return Message::find($id);
    }

    public function createFromArray(array $data): Message
    {
        return Message::create($data);
    }

    public function getMessagesByChatIdOffsetLimit(int $chatId, int $offset, int $limit)
    {
        return Message::select('*')
            ->where('chat_id', '=', $chatId)
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }

    public function setReadStatusMessages(int $chatId, int $userId)
    {
        return Message::where('chat_id', $chatId)
            ->where('read_status', Message::UNREAD_STATUS)
            ->where('user_id', '!=', $userId)
            ->update(['read_status' => Message::READ_STATUS]);
    }

    public function getUnreadMessagesCount(int $chatId, int $userId): int
    {
        return Message::where('chat_id', '=', $chatId)
            ->where('user_id', '!=', $userId)
            ->where('read_status', '=', Message::UNREAD_STATUS)
            ->count();
    }

    public function getMessagesCount(int $chatId):int
    {
        return Message::where('chat_id', $chatId)->count();
    }

    public function getLastMessages(int $chatId, int $messagesCount, int $paginateVar)
    {
        return Message::where('chat_id', $chatId)
            ->skip($messagesCount - $paginateVar)
            ->take($paginateVar)
            ->get();
    }
}
