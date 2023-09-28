<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Users\UsersService;
use Livewire\Component;

class ChatboxHeader extends Component
{
    public $chatName;
    public $selectedChat;

    public function getListeners()
    {
        return ['refresh' => '$refresh', 'refreshHeader'];
    }

    private function getUsersService(): UsersService
    {
        return app(UsersService::class);
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    public function refreshHeader(Chat $selectedChat)
    {
        $this->selectedChat = $selectedChat;
    }

    public function render()
    {
        if($this->selectedChat->name){
            $this->chatName = $this->selectedChat->name;
        } else {
            $this->chatName = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->name;
        }

        return view('livewire.chat.chatbox-header');
    }
}
