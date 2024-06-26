<?php

namespace App\Livewire\Chat\ChatList;

use App\Events\ChatCreate;
use App\Events\MarkAsOffline;
use App\Events\MarkAsOnline;
use App\Events\MessageRead;
use App\Events\ReceiveMarkAsOnline;
use App\Livewire\Validators\HtmlValidator;
use App\Models\Chat;
use App\Models\Message;
use App\Services\Chats\ChatsService;
use App\Services\Messages\MessagesService;
use App\Services\Users\UsersService;
use Illuminate\Support\Str;
use Livewire\Component;

class ChatList extends Component
{
    public $auth_id;

    public $chats;

    public $receiverInstance;

    public $selectedChat;

    public $selectedFirstChatFlag = false;

    public function getListeners()
    {
        $auth_id = auth()->id();

        return [
            "echo:online,MarkAsOnline" => 'markChatAsOnline',
            "echo:online,MarkAsOffline" => 'markChatAsOffline',
            "echo-private:chat.{$auth_id},ChatCreate" => 'refreshChatList',
            "echo:online.{$auth_id},ReceiveMarkAsOnline" => 'markReceiveChatAsOnline',
            "echo-private:chat.{$auth_id},ChatDelete" => 'refreshChatList',
            "echo-private:chat.{$auth_id},MessageRead" => 'refreshChatList',
            "echo-private:user-delete.{$auth_id},UserDelete" => 'refreshChatList',
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            'chatUserSelected', 'resetChat', 'refreshChatList', 'sendEventMarkChatAsOffline', 'searchChats', 'createConversation'];
    }

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

    private function getHtmlValidator(): HtmlValidator
    {
        return app(HtmlValidator::class);
    }

    public function broadcastMessageRead(): void
    {
        $receivers = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->get();

        if(!$receivers->count()) {
            return;
        }

        // Send messages read to all receivers connections
        foreach ($receivers as $receiver){
            broadcast(new MessageRead($this->selectedChat->id, $receiver->id));
        }
    }

    public function broadcastedMessageReceived($event): void
    {
        $this->dispatch('refreshChatList');

        if($event['user']['id'] === auth()->id()) {
            return;
        }

        if(!$this->selectedChat) {
            $this->dispatch('notify', ['user' => ['name' => $event['user']['name']]]);
            return;
        }

        if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {
            $broadcastedMessage = $this->getMessagesService()->find($event['message']['id']);

            $broadcastedMessage->read_status = 1;
            $broadcastedMessage->save();

            $this->getMessagesService()->setReadStatusMessagesForConversation($this->selectedChat->id, auth()->id());
        }
    }

    public function markReceiveChatAsOnline($event): void
    {
        $user_id = $this->auth_id;

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['receiver_user_id']);

        if ($chat) {
            $this->dispatch('markChatCircleAsOnline', $chat->id);
        }
    }

    public function markChatAsOnline($event): void
    {
        $user_id = $this->auth_id;

        if ($user_id === $event['user_id']) {
            return;
        }

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['user_id']);

        if(!$chat){
            return;
        }

        broadcast(event: new ReceiveMarkAsOnline(
            $event['user_id'],
            $user_id,
        ));

        $this->dispatch('markChatCircleAsOnline', $chat->id);
    }

    public function markChatAsOffline($event): void
    {
        $user_id = $this->auth_id;

        $chat = $this->getChatsService()->findChatBetweenTwoUsers($user_id, $event['user_id']);

        $user = $this->getUsersService()->find($event['user_id']);
        $user->last_seen = now();
        $user->save();

        if ($chat) {
            $this->dispatch('markChatCircleAsOffline', $chat->id);
        }
    }

    public function sendEventMarkChatAsOffline(): void
    {
        broadcast(event: new MarkAsOffline(
            $this->auth_id,
        ));
    }

    public function searchChats($chatName): void
    {
        $chatName = trim($chatName);

        if ($chatName === '') {
            $this->refreshChatList();
            return;
        }

        $chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);
        $this->chats = [];

        foreach ($chats as $chat) {

            if ($chat->chat_type === Chat::CONVERSATION) {
                $chatNameTmp = $chat->name;
            } else {
                $receiver = $this->getChatsService()->getChatReceivers($chat->id, $this->auth_id)->first();

                // If receiver user account was deleted
                if (!$receiver) {
                    $chatNameTmp = 'Deleted Account';
                } else {
                    $chatNameTmp = $receiver->name;
                }
            }

            if (Str::startsWith(mb_strtolower($chatNameTmp, 'UTF-8'), mb_strtolower($chatName, 'UTF-8'))) {
                $this->chats [] = $chat;
            }
        }
    }

    public function createConversation($conversationName): void
    {
        $createdChat = $this->getChatsService()->createFromArray([
            'name' => $conversationName,
            'chat_type' => Chat::CONVERSATION,
        ]);

        $createdChat->users()->attach(auth()->user());

        broadcast(event: new ChatCreate($createdChat, $this->auth_id));
    }

    public function resetChat(): void
    {
        $this->selectedChat = null;
        $this->receiverInstance = null;
    }

    public function refreshChatList(): void
    {
        $this->chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);
    }

    public function chatUserSelected(Chat $chat): void
    {
        $this->selectedChat = $chat;

        $this->dispatch('loadChat', $this->selectedChat);
        $this->dispatch('updateSendMessage', $this->selectedChat);

        $this->getMessagesService()->setReadStatusMessages($chat->id, $this->auth_id);

        if ($chat->chat_type === Chat::PRIVATE) {
            $this->broadcastMessageRead();
            return;
        }

        $this->getMessagesService()->setReadStatusMessagesForConversation($this->selectedChat->id, $this->auth_id);

        $this->broadcastMessageRead();
    }

    public function getChatUserInstance(Chat $chat, $request)
    {
        $this->auth_id = auth()->id();

        $this->receiverInstance = $this->getChatsService()->getChatReceivers($chat->id, $this->auth_id)->first();

        if (!$this->receiverInstance) {
            return null;
        }

        if (isset($request)) {
            return $this->receiverInstance->$request;
        }

        return null;
    }

    public function getMessageContent(?Message $lastMessage): ?string
    {
        if (! $lastMessage) {
            return null;
        }

        $content = $lastMessage->content;

        $lang = $this->getChatsService()->getLangForChat(
            $lastMessage->chat()->first()->id,
            auth()->id(),
        );

        if ($lang && $lastMessage->user_id !== auth()->id()) {
            $content = $lastMessage->translations[$lang] ?? $lastMessage->content;
        }

        return $this->getHtmlValidator()->customHtmlspecialcharsForImg($content);
    }

    public function mount(): void
    {
        $this->auth_id = auth()->id();

        $this->chats = $this->getChatsService()->getChatsOrderByDesc($this->auth_id);

        broadcast(event: new MarkAsOnline($this->auth_id));
    }
}
