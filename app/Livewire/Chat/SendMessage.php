<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Messages\MessagesService;
use Livewire\Component;

class SendMessage extends Component
{
    public $selectedChat;
    public $receiverInstance;
    public $body;
    public $createdMessage;
    protected $listeners = ['updateSendMessage', 'dispatchMessageSent'];

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    public function updateSendMessage(Chat $chat, User $receiver)
    {
        $this->selectedChat = $chat;
        $this->receiverInstance = $receiver;
    }

    function sendMessage()
    {
        if ($this->body == null) {
            return null;
        }

        $this->createdMessage = $this->getMessagesService()->createFromArray([
            'chat_id' => $this->selectedChat->id,
            'user_id' => auth()->id(),
            'value' => $this->body,
        ]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->dispatch('pushMessage', $this->createdMessage->id);

        $this->dispatch('refresh');
        $this->dispatch('refreshChatList');

        $this->reset('body');

        $this->dispatch('scroll-bottom');

        broadcast(event: new MessageSent(
            auth()->user(),
            $this->createdMessage,
            $this->selectedChat,
            $this->selectedChat->getReceiver()->id));
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
