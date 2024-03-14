<?php

namespace App\Services\Messages;

use App\Models\Message;
use App\Services\Messages\Repositories\MessageRepository;
use Illuminate\Support\Collection;

class MessagesService
{
    public function __construct(
        private readonly MessageRepository $messageRepository,
    )
    {
    }

    public function find(int $id): ?Message
    {
        return $this->messageRepository->find($id);
    }

    public function createFromArray(array $data): Message
    {
        return $this->messageRepository->createFromArray($data);
    }

    public function getMessagesByChatIdOffsetLimit(int $chatId, int $offset, int $limit)
    {
        return $this->messageRepository->getMessagesByChatIdOffsetLimit($chatId, $offset, $limit);
    }

    public function setReadStatusMessages(int $chatId, int $userId): void
    {
        $this->messageRepository->setReadStatusMessages($chatId, $userId);
    }

    public function getUnreadMessagesCount(int $chatId, int $userId): int
    {
        return $this->messageRepository->getUnreadMessagesCount($chatId, $userId);
    }

    public function setReadStatusMessagesForConversation(int $chatId, int $userId): void
    {
        $this->messageRepository->setReadStatusMessagesForConversation($chatId, $userId);
    }

    public function getUnreadMessagesCountForConversation(int $chatId, int $userId): int
    {
        return $this->messageRepository->getUnreadMessagesCountForConversation($chatId, $userId);
    }

    public function getMessagesCount(int $chatId):int
    {
        return $this->messageRepository->getMessagesCount($chatId);
    }

    /**
     * @param int $chatId
     * @param int $messagesCount
     * @param int $paginateVar
     * @return Collection<Message>
     */
    public function getMessages(int $chatId, int $messagesCount, int $paginateVar): Collection
    {
        return $this->messageRepository->getMessages($chatId, $messagesCount, $paginateVar);
    }

    public function updateTranslations(Message $message, string $translatedContent, string $lang): void
    {
        $this->messageRepository->updateTranslations($message, $translatedContent, $lang);
    }
}
