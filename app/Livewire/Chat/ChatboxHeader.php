<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatboxHeader extends Component
{
    public $receiverInstance;

    public function getListeners()
    {
        return [
            'refresh' => '$refresh', 'header',
        ];
    }

    public function header(){
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.chat.chatbox-header');
    }
}
