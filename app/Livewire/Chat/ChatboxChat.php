<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
use App\Livewire\Validators\HtmlValidator;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;


class ChatboxChat extends Component
{
    public $messages;
    public $selectedChat;
    public $paginateVar = 20;
    public $messages_count;
    public $height;

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'refresh' => '$refresh', 'pushMessage', 'setMessages', 'loadMore', 'updateHeight', 'refreshChat', 'broadcastMessageRead'
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
        if ($this->selectedChat) {
            if ((int)$this->selectedChat->id === (int)$event['chat_id']) {
                $this->dispatch('markMessageAsRead');
            }
        }
    }

    public function broadcastedMessageReceived($event): void
    {
        if($event['user']['id'] === auth()->id()) {
            return;
        }

        $this->dispatch('refresh');

        $broadcastedMessage = $this->getMessagesService()->find($event['message']['id']);

        if ($this->selectedChat) {

            if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {

                $broadcastedMessage->read_status = 1;
                $broadcastedMessage->save();

                $this->pushMessage($broadcastedMessage->id);

                $this->dispatch('broadcastMessageRead');
            }

        }else {
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

    public function pushMessage(int $messageId): void
    {
        $newMessage = $this->getMessagesService()->find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
    }

    public function updatedHeight(): void
    {
        $this->dispatch('updatedHeight', $this->height);
    }

    public function refreshChat(Chat $selectedChat): void
    {
        $this->selectedChat = $selectedChat;
        $this->paginateVar = 20;
        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);
        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);
        $this->dispatch('rowChatToBottom');
        $this->dispatch('chatSelectedGetHeight');
    }

    public function updateHeight($height): void
    {
        $this->height = $height;
    }

    public function loadMore(): void
    {
        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        if ($this->messages_count < $this->paginateVar)
        {
            return;
        }

        $this->paginateVar += 20;

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);

        $this->updatedHeight();
    }

    public function customHtmlspecialcharsForImg($message)
    {
        return $this->getHtmlValidator()->customHtmlspecialcharsForImg($message);
    }

    public function mount()
    {
        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);

        $this->dispatch('chatSelectedGetHeight');
        $this->dispatch('rowChatToBottom');
        $this->dispatch('scrollEventHandle');
    }

    public function render()
    {
        return view('livewire.chat.chatbox-chat');
    }

}
