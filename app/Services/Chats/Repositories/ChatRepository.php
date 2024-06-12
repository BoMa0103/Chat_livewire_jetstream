<?php

namespace App\Services\Chats\Repositories;

use App\Models\Chat;
use App\Models\User;

interface ChatRepository
{
    public function find(int $id): ?Chat;

    public function createFromArray(array $data): Chat;

    public function findChatBetweenTwoUsers(int $userIdFirst, int $userIdSecond): ?Chat;

    public function getChatsOrderByDesc(int $userId);

    public function getChatReceivers(int $chatId, int $senderUserId);

    public function deleteChat(int $chatId);

    public function setLangForChat(int $chatId, int $userId, string $langCode): void;

    public function getLangForChat(int $chatId, int $userId): ?string;
}
