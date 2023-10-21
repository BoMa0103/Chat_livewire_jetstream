<?php

namespace App\Livewire\Chat\UserList;

use App\Events\ChatCreate;
use App\Events\MarkAsOnline;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use App\Services\Users\UsersService;
use Livewire\Component;

class UserList extends Component
{
    public $users;

    public function getListeners()
    {
        return [
            "echo:users,UserCreate",
        ];
    }

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    private function getUsersService(): UsersService
    {
        return app(UsersService::class);
    }

    public function checkChat(int $userId): void
    {
        $checkedChat = $this->getChatsService()->findChatBetweenTwoUsers(auth()->id(), $userId);

        if ($checkedChat) {
            return;
        }

        $createdChat = $this->getChatsService()->createFromArray([
            'chat_type' => Chat::PRIVATE
        ]);

        $createdChat->users()->attach($userId);
        $createdChat->users()->attach(auth()->user());

        $this->sendChatCreateEvents($createdChat, $userId);
    }

    private function sendChatCreateEvents(Chat $createdChat, int $userId): void
    {
        // Send event to all receiver user connections
        broadcast(event: new ChatCreate($createdChat->id, $userId));
        // Send event to all this user connections
        broadcast(event: new ChatCreate($createdChat->id, auth()->id()));

        broadcast(event: new MarkAsOnline(auth()->id()));
    }

    public function render()
    {
        $this->users = $this->getUsersService()->getUsersWithoutUserWithId(auth()->id());

        return view('livewire.chat.user-list.user-list');
    }
}
