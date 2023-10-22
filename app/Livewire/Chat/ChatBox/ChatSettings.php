<?php

namespace App\Livewire\Chat\ChatBox;

use App\Events\ChatDelete;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatSettings extends Component
{
    public $selectedChat;

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
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

    public function render()
    {
        return view('livewire.chat.chat-box.chat-settings');
    }
}
