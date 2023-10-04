<?php

namespace App\Livewire\Chat;

use App\Services\Chats\ChatsService;
use Livewire\Component;

class ChatSettings extends Component
{
    public $selectedChat;

    protected $listeners = ['deleteChat'];

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    // @todo fix delete chat
    public function deleteChat()
    {
//        $this->selectedChat->delete();
//        $this->selectedChat = null;

        $this->dispatch('reloadChatPage');
    }

    public function render()
    {
        return view('livewire.chat.chat-settings');
    }
}
