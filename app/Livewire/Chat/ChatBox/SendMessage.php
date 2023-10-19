<?php

namespace App\Livewire\Chat\ChatBox;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Overtrue\LaravelEmoji\Emoji;

class SendMessage extends Component
{
    use WithFileUploads;

    public $selectedChat;

    public $createdMessage;

    public $body;

    #[Rule('max:10000')]
    public $file;

    protected $listeners = ['updateSendMessage', 'dispatchMessageSent', 'sendMessage'];

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    public function updateSendMessage(Chat $chat): void
    {
        $this->selectedChat = $chat;
    }

    public function sendMessage()
    {
        $this->dispatch('error', ['message' => 'Tetst']);

        if ($this->file) {
//            $filename = $this->uploadedFile->store('/', 'files');
        }

        if ($this->body === null || trim($this->body) == '') {
            return null;
        }

        $this->createdMessage = $this->getMessagesService()->createFromArray(['chat_id' => $this->selectedChat->id, 'user_id' => auth()->id(), 'content' => Emoji::toImage($this->body)]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->reset('body');

        $this->sendEvents();
    }

    private function sendEvents(): void
    {
        // Send Message to all connections with this user
        broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, auth()->id()));

        if ($this->selectedChat->chat_type === Chat::PRIVATE) {
            $receiverId = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->id;

            // Send Message to all connections with receiver user
            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiverId));

            return;
        }

        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        foreach ($receivers as $receiver) {
            $receiver->messagesToMany()->attach($this->createdMessage->id, ['chat_id' => $this->selectedChat->id]);

            // Send Message to all receivers connections
            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiver->id));
        }
    }

    public function mount(): void
    {
        $this->dispatch('send-message-loaded');
    }

    public function render()
    {
        return view('livewire.chat.chat-box.send-message');
    }
}
