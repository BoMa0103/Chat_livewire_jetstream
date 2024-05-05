<?php

namespace App\Livewire\Chat\ChatBox;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use App\Services\Translations\TranslationsService;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Overtrue\LaravelEmoji\Emoji;

class SendMessage extends Component
{
    use WithFileUploads;

    public Chat $selectedChat;

    public Message $createdMessage;

    public $body;

    #[Rule('max:10000')]
    public $file;

    protected $listeners = ['updateSendMessage', 'dispatchMessageSent', 'sendMessage'];

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    private function getTranslationsService(): TranslationsService
    {
        return app(TranslationsService::class);
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
        if ($this->body === null || trim($this->body) == '') {
            return null;
        }

        $this->createdMessage = $this->getMessagesService()->createFromArray([
            'chat_id' => $this->selectedChat->id,
            'user_id' => auth()->id(),
            'content' => Emoji::toImage($this->body)
        ]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->reset('body');

        $this->sendMessageSentEvents();
    }

    private function sendMessageSentEvents(): void
    {
        // Send Message to all connections with THIS user
        broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, auth()->id()));

        if ($this->selectedChat->chat_type === Chat::PRIVATE) {
            $receiver = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first();
            // If receiver account was deleted
            if (!$receiver) {
                return;
            }
            $this->translateMessageToReceiverLang($receiver);
            // Send Message to all connections with receiver user
            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiver->id));

            return;
        }

        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        foreach ($receivers as $receiver) {
            $receiver->messagesToMany()->attach($this->createdMessage->id, ['chat_id' => $this->selectedChat->id]);
            $this->translateMessageToReceiverLang($receiver);
            // Send Message to all receivers connections
            broadcast(event: new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $receiver->id));
        }
    }

    public function mount(): void
    {
        $this->dispatch('send-message-loaded');
    }

    private function translateMessageToReceiverLang(User $receiver): void
    {
        $lang = $this->getChatsService()->getLangForChat(
            $this->selectedChat->id,
            $receiver->id,
        );

        if (! $lang) {
            return;
        }

        $translatedContent = $this->getTranslationsService()->translateText(
            $this->createdMessage->content,
            $lang,
        );

        $this->getMessagesService()->updateTranslations(
            $this->createdMessage,
            $translatedContent,
            $lang,
        );
    }
}
