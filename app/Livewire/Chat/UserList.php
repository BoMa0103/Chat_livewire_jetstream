<?php

namespace App\Livewire\Chat;

use App\Events\ChatCreate;
use App\Events\MarkAsOnline;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use Livewire\Component;

class UserList extends Component
{
    public $users;

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    public function checkChat(int $userId)
    {
        $checkedChat = $this->getChatsService()->findChatBetweenTwoUsers(auth()->user()->id, $userId);

        if (!$checkedChat) {
            $createdChat = $this->getChatsService()->createFromArray([
                'user_id_first' => auth()->user()->id,
                'user_id_second' => $userId,
            ]);

            broadcast(event: new ChatCreate(
                $createdChat->id,
                $userId));

            $this->dispatch('refreshChatList');

            broadcast(event: new MarkAsOnline(
                auth()->user()->id));
        }
    }

    public function render()
    {
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
        return view('livewire.chat.user-list');
    }
}
