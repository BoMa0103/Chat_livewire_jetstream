<?php

namespace App\Services\Messages\Repositories;

use App\Models\Message;

interface MessageRepository
{
    public function find(int $id): ?Message;

    public function createFromArray(array $data): Message;

    public function getMessagesByChatIdOffsetLimit(int $chatId, int $offset, int $limit);

    public function setReadStatusMessages(int $chatId, int $userId): void;

    public function getUnreadMessagesCount(int $chatId, int $userId): int;

    public function setReadStatusMessagesForConversation(int $chatId, int $userId): void;

    public function getUnreadMessagesCountForConversation(int $chatId, int $userId): int;

    public function getMessagesCount(int $chatId): int;

    public function getMessages(int $chatId, int $messagesCount, int $paginateVar);

    public function updateTranslations(Message $message, string $translatedContent, string $lang): void;
}
