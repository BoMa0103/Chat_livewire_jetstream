<?php

namespace App\Livewire\Chat;

use App\Events\ChatDelete;
use App\Models\Chat;
use App\Services\Chats\ChatsService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ChatSettings extends Component
{
    public $selectedChat;

    protected $listeners = ['deleteChat'];

    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }

    public function deleteChat(): void
    {
        $deletedChatId = $this->selectedChat->id;

        $receivers = $this->getChatsService()->getChatReceivers($deletedChatId, auth()->id())->get();

        $this->selectedChat->delete();
        DB::table('chat_user')->where('chat_id', $this->selectedChat->id)->delete();

        $this->dispatch('resetChat');
        $this->dispatch('resetMessage');
        $this->dispatch('refreshChatList');

        // Browser event
        $this->dispatch('hideMessageInput');

        if ($this->selectedChat->chat_type === Chat::PRIVATE) {
            broadcast(event: new ChatDelete(
                $receivers->first()->id, $deletedChatId,
            ));
        } else {
            foreach ($receivers as $receiver) {
                broadcast(event: new ChatDelete(
                    $receiver->id, $deletedChatId,
                ));
            }
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-settings');
    }
}
