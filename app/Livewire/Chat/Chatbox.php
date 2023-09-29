<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedChat;
    public $messages;
    public $paginateVar = 20;
    public $messages_count;
    public $receivers;

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadChat', 'pushMessage', 'broadcastMessageRead', 'resetChat',];
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
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

        $this->dispatch('refreshChatList');
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

    public function loadChat(Chat $chat): void
    {
        $this->selectedChat = $chat;

        $this->receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        $this->messages_count = $this->getMessagesService()->getMessagesCount($this->selectedChat->id);

        $this->messages = $this->getMessagesService()->getLastMessages($this->selectedChat->id, $this->messages_count, $this->paginateVar);

        // don't send messages directly because they parse to array
        $this->dispatch('refreshChat', $this->selectedChat, $this->messages_count, $this->paginateVar);
        $this->dispatch('refreshHeader', $this->selectedChat);
        $this->dispatch('refreshUserListForConversation', $this->selectedChat);

        $this->dispatch('chatSelected');
    }

    public function resetChat(): void
    {
        $this->selectedChat = null;
        $this->receivers = null;
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
