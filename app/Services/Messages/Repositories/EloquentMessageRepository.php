<?php

namespace App\Services\Messages\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public function setReadStatusMessages(int $chatId, int $userId): void
    {
        Message::where('chat_id', $chatId)
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

    public function setReadStatusMessagesForConversation(int $chatId, int $userId): void
    {
        DB::table('message_user')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->update(['read_status' => 1]);
    }

    public function getUnreadMessagesCountForConversation(int $chatId, int $userId): int
    {
        return DB::table('message_user')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->where('read_status', 0)
            ->count();
    }

    public function getMessagesCount(int $chatId): int
    {
        return Message::where('chat_id', $chatId)->count();
    }

    /**
     * @param int $chatId
     * @param int $messagesCount
     * @param int $paginateVar
     * @return Collection<Message>
     */
    public function getMessages(int $chatId, int $messagesCount, int $paginateVar): Collection
    {
        return Message::where('chat_id', $chatId)
            ->skip($messagesCount - $paginateVar)
            ->take($paginateVar)
            ->get();
    }
    public function updateTranslations(Message $message, string $translatedContent, string $lang): void
    {
        $translations = array_merge(
            $message->translations ?? [], [
                $lang => $translatedContent,
        ]);

        Message::whereId($message->id)
            ->update([
                'translations' => $translations,
            ]);
    }

}
