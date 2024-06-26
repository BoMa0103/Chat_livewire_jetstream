<?php

namespace App\Services\Chats;

use App\Models\Chat;
use App\Services\Chats\Repositories\ChatRepository;

class ChatsService
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
    ) {
    }

    public function find(int $id): ?Chat
    {
        return $this->chatRepository->find($id);
    }

    public function createFromArray(array $data): Chat
    {
        return $this->chatRepository->createFromArray($data);
    }

    public function findChatBetweenTwoUsers(int $userIdFirst, int $userIdSecond): ?Chat
    {
        return $this->chatRepository->findChatBetweenTwoUsers($userIdFirst, $userIdSecond);
    }

    public function getChatsOrderByDesc(int $userId)
    {
        return $this->chatRepository->getChatsOrderByDesc($userId);
    }

    public function getChatReceivers(int $chatId, int $senderUserId)
    {
        return $this->chatRepository->getChatReceivers($chatId, $senderUserId);
    }

    public function deleteChat(int $chatId)
    {
        return $this->chatRepository->deleteChat($chatId);
    }

    public function setLangForChat(int $chatId, int $userId, string $langCode): void
    {
        $this->chatRepository->setLangForChat($chatId, $userId, $langCode);
    }

    public function getLangForChat(int $chatId, int $userId): ?string
    {
        return $this->chatRepository->getLangForChat($chatId, $userId);
    }
}
