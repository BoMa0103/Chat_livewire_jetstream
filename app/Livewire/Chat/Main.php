<?php

namespace App\Livewire\Chat;

use App\Services\Users\UsersService;
use Livewire\Component;

class Main extends Component
{
    public $user_id;

    public $theme;

    public $listeners = ['changeTheme'];

    private function getUsersService(): UsersService
    {
        return app(UsersService::class);
    }

    public function changeTheme($theme): void
    {
        $user = $this->getUsersService()->find($this->user_id);
        $user->theme = $theme;
        $user->save();
    }

    public function mount(): void
    {
        $this->user_id = auth()->user()->id;
        $this->theme = $this->getUsersService()->find($this->user_id)->theme;

        $this->dispatch('setTheme', $this->theme);
    }

    public function render()
    {
        return view('livewire.chat.main');
    }
}
