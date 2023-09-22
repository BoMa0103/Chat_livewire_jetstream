<?php

namespace App\Livewire\Chat;

use App\Events\MarkAsOffline;
use App\Events\MarkAsOnline;
use App\Events\ReceiveMarkAsOnline;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use App\Services\Users\UsersService;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Events\ChatCreate;

class ChatList extends Component
{
    public $auth_id;
    public $chats;
    public $receiverInstance;
    public $name;
    public $selectedChat;
    public $selectedFirstChatFlag = false;

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    private function getUsersService(): UsersService
    {
        return app(UsersService::class);
    }

    private function getMessagesService(): MessagesService
    {
        return app(MessagesService::class);
    }

    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            "echo-private:chat.{$auth_id},ChatCreate" => 'refreshChatList',
            "echo:online,MarkAsOnline" => 'markChatAsOnline',
            "echo:online,MarkAsOffline" => 'markChatAsOffline',
            "echo:online.{$auth_id},ReceiveMarkAsOnline" => 'markReceiveChatAsOnline',
            'chatUserSelected', 'refresh' => '$refresh', 'resetChat', 'refreshChatList', 'sendEventMarkChatAsOffline', 'searchChats'
        ];
    }

    public function searchChats($chatName)
    {
        $chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);
        $this->chats = [];

        foreach ($chats as $chat) {

            if($chat->user_id_first === $this->auth_id) {
                $chatNameTmp = $this->getUsersService()->find($chat->user_id_second)->name;
                if (Str::startsWith(strtolower($chatNameTmp), strtolower($chatName))) {
                    $this->chats [] = $chat;
                }
            }else {
                $chatNameTmp = $this->getUsersService()->find($chat->user_id_first)->name;
                if (Str::startsWith(strtolower($chatNameTmp), strtolower($chatName))) {
                    $this->chats [] = $chat;
                }
            }

        }
    }

    public function resetChat(){
        $this->selectedChat = null;
        $this->receiverInstance = null;
    }

    public function markReceiveChatAsOnline($event)
    {
        $user_id = $this->auth_id;

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['receiver_user_id']);

        if($chat){
            $this->dispatch('markChatCircleAsOnline', $chat->id);
        }
    }

    public function markChatAsOnline($event)
    {
        $user_id = $this->auth_id;

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['user_id']);

        if($chat){
            broadcast(event: new ReceiveMarkAsOnline(
                $event['user_id'],
                $user_id));

            $this->dispatch('markChatCircleAsOnline', $chat->id);
        }
    }

    public function markChatAsOffline($event)
    {
        $user_id = $this->auth_id;

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['user_id']);

        if($chat){
            $this->dispatch('markChatCircleAsOffline', $chat->id);
        }
    }

    public function sendEventMarkChatAsOffline()
    {
        broadcast(event: new MarkAsOffline(
            $this->auth_id));
    }

    public function refreshChatList() {
        $this->chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);

        $this->dispatch('refresh');
    }

    public function chatUserSelected(Chat $chat, $receiverId)
    {
        $this->selectedChat = $chat;

        $receiverInstance = $this->getUsersService()->find($receiverId);

        $this->getMessagesService()->setReadStatusMessages($chat->id, $receiverInstance->id);

        $this->dispatch('loadChat', $this->selectedChat, $receiverInstance);

        $this->dispatch('loadChatData', $this->selectedChat);

        $this->dispatch('updateSendMessage', $this->selectedChat, $receiverInstance);

        $this->dispatch('broadcastMessageRead');
    }

    public function getChatUserInstance(Chat $chat, $request)
    {
        $this->auth_id = auth()->id();

        if ($chat->user_id_first == $this->auth_id) {
            $this->receiverInstance = $this->getUsersService()->find($chat->user_id_second);
        } else {
            $this->receiverInstance = $this->getUsersService()->find($chat->user_id_first);
        }

        if (isset($request)) {
            return $this->receiverInstance->$request;
        }
    }

    public function mount()
    {
        $this->auth_id = auth()->id();

        $this->chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);

        broadcast(event: new MarkAsOnline(
            $this->auth_id));
    }

    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}
