<?php

namespace App\Livewire\Chat\ChatBox;

use App\Events\ChatDelete;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Translations\TranslationsService;
use Livewire\Component;

class ChatSettings extends Component
{
    public Chat $selectedChat;

    public array $languages;

    public ?string $selectedLangCode = null;

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    protected function getTranslationsService(): TranslationsService
    {
        return app(TranslationsService::class);
    }

    public function mount(): void
    {
        $this->languages = $this->getTranslationsService()->getSupportedLanguages();
        $this->selectedLangCode = $this->getChatsService()->getLangForChat(
            $this->selectedChat->id,
            auth()->id(),
        );
    }

    public function setLanguage(string $langCode): void
    {
        $this->getChatsService()->setLangForChat(
            $this->selectedChat->id,
            auth()->id(),
            $langCode,
        );
    }

    public function deleteChat(): void
    {
        $deletedChatId = $this->selectedChat->id;

        $receivers = $this->getChatsService()->getChatReceivers($deletedChatId, auth()->id())->get();

        $this->getChatsService()->deleteChat($this->selectedChat->id);

        $this->dispatch('resetChat');
        $this->dispatch('resetMessage');
        $this->dispatch('refreshChatList');

        $this->dispatch('hideMessageInput');

        $this->sendDeleteChatEvents($deletedChatId, $receivers);
    }

    private function sendDeleteChatEvents(int $deletedChatId, $receivers): void
    {
        // Send chat delete to all connections with THIS user
        broadcast(event: new ChatDelete(
            auth()->id(), $deletedChatId,
        ));

        if ($this->selectedChat->chat_type === Chat::PRIVATE) {
            // If receiver's account was deleted
            if (!$receivers->first()) {
                return;
            }
            // Send chat delete to all connections with receiver user
            broadcast(event: new ChatDelete(
                $receivers->first()->id, $deletedChatId,
            ));
            return;
        }

        foreach ($receivers as $receiver) {
            // Send chat delete to all receivers connections
            broadcast(event: new ChatDelete(
                $receiver->id, $deletedChatId,
            ));
        }
    }
}
