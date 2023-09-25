<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
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
        return ["echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived', "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead', 'refresh' => '$refresh', 'chat', 'pushMessage', 'loadChatData', 'setMessages', 'loadMore', 'updateHeight', 'updatedHeightEvent'];
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    function broadcastedMessageRead($event)
    {
        if ($this->selectedChat) {
            if ((int)$this->selectedChat->id === (int)$event['chat_id']) {
                $this->dispatch('markMessageAsRead');
            }
        }
    }

    function broadcastedMessageReceived($event)
    {
        $this->dispatch('refresh');

        $this->dispatch('refreshChatList');

        $broadcastedMessage = $this->getMessagesService()->find($event['message']['id']);

        if (!$this->selectedChat) {
            return;
        }

        if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {

            $broadcastedMessage->read_status = 1;
            $broadcastedMessage->save();

            $this->pushMessage($broadcastedMessage->id);

            $this->dispatch('broadcastMessageRead');
        } else {
            $this->dispatch('notify', ['user' => ['name' => $event['user']['name']]]);
        }

    }

    public function pushMessage(int $messageId)
    {
        $newMessage = $this->getMessagesService()->find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
    }

    public function updatedHeightEvent()
    {
        $this->dispatch('updatedHeight', $this->height);
    }

    public function chat()
    {
        $this->dispatch('refresh');
    }

    public function updateHeight($height)
    {
        $this->height = $height;
    }

    public function loadMore()
    {
        $this->paginateVar += 20;

        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);


        $this->dispatch('updatedHeightEvent');
    }

    public function loadChatData(Chat $chat)
    {
        $this->selectedChat = $chat;
    }

    public function mount()
    {
        $this->dispatch('chatSelectedGetHeight');
        $this->dispatch('rowChatToBottom');
        $this->dispatch('scrollEventHandle');
    }

    public function render()
    {
        return view('livewire.chat.chatbox-chat');
    }

}
