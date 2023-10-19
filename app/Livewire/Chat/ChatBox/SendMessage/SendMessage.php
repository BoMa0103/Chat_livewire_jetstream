<?php

namespace App\Livewire\Chat\ChatBox\SendMessage;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;
use Overtrue\LaravelEmoji\Emoji;

class SendMessage extends Component
{
    public $selectedChat;

    public $createdMessage;

    public $body;

    protected $listeners = ['updateSendMessage', 'dispatchMessageSent', 'sendMessage'];

    public function updateSendMessage(Chat $chat): void
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

    public function sendMessage()
    {
        if ($this->body === null || trim($this->body) == '') {
            return null;
        }

        $this->createdMessage = $this->getMessagesService()->createFromArray(['chat_id' => $this->selectedChat->id, 'user_id' => auth()->id(), 'content' => Emoji::toImage($this->body)]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->reset('body');

        broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, auth()->id()));

        if(!$this->selectedChat->name) {
            $receiverId = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->id;

            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiverId));

            return;
        }

        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        foreach ($receivers as $receiver) {
            $receiver->messagesToMany()->attach($this->createdMessage->id, ['chat_id' => $this->selectedChat->id]);

            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiver->id));
        }
    }

    public function mount(): void
    {
        $this->dispatch('send-message-loaded');
    }

    public function render()
    {
        return view('livewire.chat.chat-box.send-message.send-message');
    }
}
