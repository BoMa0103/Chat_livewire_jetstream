<?php

namespace App\Services\Chats\Repositories;

use App\Models\Chat;

interface ChatRepository
{
    public function find(int $id): ?Chat;

    public function createFromArray(array $data): Chat;

    public function findChatBetweenTwoUsers(int $userIdFirst, int $userIdSecond): ?Chat;

    public function getChatsOrderByDesc(int $userId);

    public function getChatReceivers(int $chatId, int $senderUserId);

    public function deleteChat(int $chatId);
}
