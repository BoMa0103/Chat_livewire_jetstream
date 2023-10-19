<?php

namespace App\Livewire\Chat\ChatBox;

use App\Models\Chat;
use App\Services\Chats\ChatsService;
use Livewire\Component;

class ChatboxHeader extends Component
{
    public $chatName;

    public $lastSeen;

    public $selectedChat;

    protected $listeners = ['refreshHeader'];

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    public function refreshHeader(Chat $selectedChat)
    {
        $this->selectedChat = $selectedChat;
    }

    private function updateLastSeen(): void
    {
        $lastSeen = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->last_seen;

        $lastSeenTimestamp = strtotime($lastSeen);
        $currentTimestamp = time();

        $timeDifference = $currentTimestamp - $lastSeenTimestamp;

        if ($timeDifference < 60) {
            $this->lastSeen = 'last seen just now';
        } elseif ($timeDifference < 3600) {
            $minutesAgo = floor($timeDifference / 60);
            $this->lastSeen = "last seen $minutesAgo minutes ago";
        } elseif ($timeDifference < 86400) {
            $hoursAgo = floor($timeDifference / 3600);
            $this->lastSeen = "last seen $hoursAgo hours ago";
        } else {
            $daysAgo = floor($timeDifference / 86400);
            $this->lastSeen = "last seen $daysAgo days ago";
        }
    }

    public function render()
    {
        if($this->selectedChat->chat_type === Chat::CONVERSATION){
            $this->chatName = $this->selectedChat->name;
        } else {
            $this->chatName = $this->getChatsService()->getChatReceivers($this->selectedChat->id, auth()->id())->first()->name;

            $this->updateLastSeen();
        }

        return view('livewire.chat.chat-box.chatbox-header');
    }
}
