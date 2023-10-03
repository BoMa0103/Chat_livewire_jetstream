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
        $auth_id = auth()->user()->id;

        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            'loadChat', 'resetChat', 'broadcastMessageRead'
        ];
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    public function loadChat(Chat $chat): void
    {
        $this->selectedChat = $chat;

        $this->dispatch('refreshChat', $this->selectedChat);
        $this->dispatch('refreshHeader', $this->selectedChat);
        $this->dispatch('refreshUserListForConversation', $this->selectedChat);
    }

    public function resetChat(): void
    {
        $this->selectedChat = null;
    }

    public function broadcastMessageRead(): void
    {
        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        if(!$receivers->count()) {
            return;
        }

        foreach ($receivers as $receiver){
            broadcast(new MessageRead($this->selectedChat->id, $receiver->id));
        }
    }

    public function broadcastedMessageReceived($event): void
    {
        if($event['user']['id'] === auth()->id()) {
            return;
        }

        if(!$this->selectedChat) {
            $this->dispatch('notify', ['user' => ['name' => $event['user']['name']]]);
        }
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
