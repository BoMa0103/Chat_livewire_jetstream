<?php

namespace App\Livewire\Chat\ChatBox;

use App\Events\MessageRead;
use App\Livewire\Validators\HtmlValidator;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;

class ChatboxMessages extends Component
{
    public $messages;

    public $selectedChat;
    public $paginateVar = 20;
    public $messagesCount;

    public $scrollHeight;

    public function getListeners()
    {
        $auth_id = auth()->id();
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            "echo-private:chat.{$auth_id},ChatDelete" => 'chatDelete',
            'pushMessage', 'setMessages', 'loadMore', 'updateHeight', 'refreshChat', 'broadcastMessageRead'
        ];
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    private function getHtmlValidator(): HtmlValidator
    {
        return app(HtmlValidator::class);
    }

    public function broadcastedMessageRead($event): void
    {
        if (!$this->selectedChat) {
            return;
        }

        if ((int)$this->selectedChat->id === (int)$event['chat_id']) {
            $this->dispatch('markMessageAsRead');
        }
    }

    public function broadcastedMessageReceived($event): void
    {
        $broadcastedMessage = $this->getMessagesService()->find($event['message']['id']);

        if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {

            if($event['user']['id'] === auth()->id()) {
                $this->pushMessage($broadcastedMessage->id);
                return;
            }

            $broadcastedMessage->read_status = 1;
            $broadcastedMessage->save();

            $this->getMessagesService()->setReadStatusMessagesForConversation($this->selectedChat->id, auth()->id());

            $this->pushMessage($broadcastedMessage->id);

            $this->dispatch('broadcastMessageRead');
        } else {

            if($event['user']['id'] === auth()->id()) {
                return;
            }

            $this->dispatch('notify', ['user' => ['name' => $event['user']['name']]]);
        }
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

    public function chatDelete($event): void
    {
        if ($this->selectedChat->id === $event['chat_id']) {
            $this->dispatch('resetChat');
            $this->dispatch('resetMessage');

            $this->dispatch('hideMessageInput');
        }
    }

    public function pushMessage(int $messageId): void
    {
        $newMessage = $this->getMessagesService()->find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
    }

    public function updatedHeight(): void
    {
        $this->dispatch('updatedHeight', $this->scrollHeight);
    }

    public function refreshChat(Chat $selectedChat): void
    {
        $this->selectedChat = $selectedChat;
        $this->paginateVar = 20;
        $this->messagesCount = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);
        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messagesCount, $this->paginateVar);
        $this->dispatch('rowChatToBottom');
        $this->dispatch('chatSelectedGetHeight');
    }

    public function updateHeight($height): void
    {
        $this->scrollHeight = $height;
    }

    public function loadMore(): void
    {
        $this->messagesCount = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        if ($this->messagesCount < $this->paginateVar) {
            return;
        }

        $this->paginateVar += 20;

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messagesCount, $this->paginateVar);

        $this->updatedHeight();
    }

    public function customHtmlspecialcharsForImg($message)
    {
        return $this->getHtmlValidator()->customHtmlspecialcharsForImg($message);
    }

    public function mount()
    {
        $this->messagesCount = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messagesCount, $this->paginateVar);

        $this->dispatch('chatSelectedGetHeight');
        $this->dispatch('rowChatToBottom');
        $this->dispatch('scrollEventHandle');
    }

    public function render()
    {
        return view('livewire.chat.chat-box.chatbox-messages');
    }
}
