<?php

namespace Tests\Feature\Livewire\Chat\ChatBox;

use App\Livewire\Chat\ChatBox\UserListForConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Generators\ChatGenerator;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class UserListForConversationTest extends TestCase
{
    use RefreshDatabase;

    public function testAddUserToConversation()
    {
        $chat = ChatGenerator::generate();

        $user = UserGenerator::generate();
        $anotherUser = UserGenerator::generate();

        Livewire::test(UserListForConversation::class, ['selectedChat' => $chat])
            ->call('addUserToConversation', $user->id);

        $this->assertSame($user->id, ($chat->users()->get()->find($user->id))->id);
        $this->assertNull($chat->users()->get()->find($anotherUser->id));
    }
}
