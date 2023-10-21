<?php

namespace App\Livewire\Chat\ChatBox;

use Livewire\Component;

class NoChatbox extends Component
{
    // @todo redo like blade
    public function render()
    {
        return view('livewire.chat.chat-box.no-chatbox');
    }
}
