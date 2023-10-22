<?php

namespace Tests\Feature\Livewire\Chat\ChatList;

use App\Livewire\Chat\ChatList\ChatList;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class ChatListTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateConversation()
    {
        $user = UserGenerator::generate();

        auth()->login($user);

        $conversationName = fake()->name;

        Livewire::test(ChatList::class)
            ->set('auth_id', $user->id)
            ->call('createConversation', $conversationName);

        $chat = Chat::all()->first();
        $chatsCount = Chat::all()->count();

        $this->assertSame($conversationName, $chat->name);
        $this->assertSame(Chat::CONVERSATION, $chat->chat_type);
        $this->assertEquals(1, $chatsCount);
    }
}
