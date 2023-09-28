<?php

namespace App\Livewire\Chat;

use App\Events\ChatCreate;
use App\Models\Chat;
use App\Services\Users\UsersService;
use Livewire\Component;

class UserListForConversation extends Component
{
    public $selectedChat;
    public $users;

    protected $listeners = ['refreshUserListForConversation'];


    private function getUsersService(): UsersService
    {
        return app(UsersService::class);
    }

    public function addUserToConversation(int $userId)
    {
        $user = $this->getUsersService()->find($userId);

        $this->selectedChat->users()->attach($user);

        broadcast(event: new ChatCreate($this->selectedChat, $userId));
    }

    public function refreshUserListForConversation(Chat $selectedChat)
    {
        $this->selectedChat = $selectedChat;
    }

    public function render()
    {
        $this->users = $this->getUsersService()->getUsersAreNotInChat($this->selectedChat->id);

        return view('livewire.chat.user-list-for-conversation');
    }
}
