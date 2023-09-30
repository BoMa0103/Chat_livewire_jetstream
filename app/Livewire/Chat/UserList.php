<?php

namespace App\Livewire\Chat;

use App\Events\ChatCreate;
use App\Events\MarkAsOnline;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use App\Services\Users\UsersService;
use Livewire\Component;

class UserList extends Component
{
    public $users;

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
        $checkedChat = $this->getChatsService()->findChatBetweenTwoUsers(auth()->user()->id, $userId);

        if (!$checkedChat) {
            $createdChat = $this->getChatsService()->createFromArray([
                'chat_type' => Chat::PRIVATE
            ]);

            $createdChat->users()->attach($userId);
            $createdChat->users()->attach(auth()->user());

            broadcast(event: new ChatCreate($createdChat->id, $userId));

            $this->dispatch('refreshChatList');

            broadcast(event: new MarkAsOnline(auth()->user()->id));
        }
    }

    public function render()
    {
        $this->users = $this->getUsersService()->getUsersWithoutUserWithId(auth()->user()->id);
        return view('livewire.chat.user-list');
    }
}
