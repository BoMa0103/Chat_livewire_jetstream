<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
use App\Models\Chat;
use App\Models\User;
use App\Services\Messages\MessagesService;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedChat;
    public $receiverInstance;
    public $messages;
    public $paginateVar = 20;
    public $messages_count;

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadChat', 'pushMessage', 'broadcastMessageRead', 'resetChat',
        ];
    }

    public function resetChat(){
        $this->selectedChat = null;
        $this->receiverInstance = null;
        $this->dispatch('refresh');
    }

    function broadcastedMessageReceived($event)
    {
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

    public function broadcastMessageRead()
    {
        broadcast(new MessageRead(
            $this->selectedChat->id,
            $this->receiverInstance->id,
        ));
    }

    public function pushMessage(int $messageId)
    {
        $newMessage = $this->getMessagesService()->find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
    }

    public function loadChat(Chat $chat, User $receiver)
    {
        $this->selectedChat = $chat;
        $this->receiverInstance = $receiver;

        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);

        $this->dispatch('chat');
        $this->dispatch('header');

        $this->dispatch('chatSelected');
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
