<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Services\Messages\MessagesService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Overtrue\LaravelEmoji\Emoji;

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
            'refresh' => '$refresh', 'pushMessage', 'loadChatData', 'setMessages', 'loadMore', 'updateHeight', 'updatedHeightEvent', 'refreshChat'
        ];
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

        $this->dispatch('refreshChatList');

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

    public function refreshChat(Chat $selectedChat, int $messages_count, int $paginateVar)
    {
        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $messages_count, $paginateVar);
        $this->selectedChat = $selectedChat;
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
//        dump($this->selectedChat->id);
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
