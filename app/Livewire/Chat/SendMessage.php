<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;
use Overtrue\LaravelEmoji\Emoji;

class SendMessage extends Component
{
    public $selectedChat;
    public $body;
    public $createdMessage;
    protected $listeners = ['updateSendMessage', 'dispatchMessageSent'];

    public function updateSendMessage(Chat $chat)
    {
        $this->selectedChat = $chat;
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    function sendMessage()
    {
        if ($this->body == null) {
            return null;
        }

        $this->createdMessage = $this->getMessagesService()->createFromArray(['chat_id' => $this->selectedChat->id, 'user_id' => auth()->id(), 'content' => Emoji::toImage($this->body)]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->dispatch('pushMessage', $this->createdMessage->id);

        $this->dispatch('refresh');
        $this->dispatch('refreshChatList');

        $this->reset('body');

        $this->dispatch('scroll-bottom');

        if(!$this->selectedChat->name) {
            $receiverId = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->id;

            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiverId));

            return;
        }

        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        foreach ($receivers as $receiver) {
            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiver->id));
        }
    }
    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
