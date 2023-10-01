<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedChat;

    public function getListeners()
    {
        return [
            'loadChat', 'resetChat'
        ];
    }

    public function loadChat(Chat $chat): void
    {
        $this->selectedChat = $chat;

        // don't send messages directly because they parse to array
        $this->dispatch('refreshChat', $this->selectedChat);
        $this->dispatch('refreshHeader', $this->selectedChat);
        $this->dispatch('refreshUserListForConversation', $this->selectedChat);
    }

    public function resetChat(): void
    {
        $this->selectedChat = null;
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
