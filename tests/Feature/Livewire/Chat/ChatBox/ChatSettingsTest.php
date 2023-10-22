<?php

namespace Tests\Feature\Livewire\Chat\ChatBox;

use App\Livewire\Chat\ChatBox\ChatSettings;
use App\Livewire\Chat\ChatBox\SendMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Generators\ChatGenerator;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class ChatSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteChat()
    {
        $chat = ChatGenerator::generate();
        $anotherChat = ChatGenerator::generate();

        $userFirst = UserGenerator::generate();
        $userSecond = UserGenerator::generate();

        $chat->users()->attach($userFirst);
        $chat->users()->attach($userSecond);
        $anotherChat->users()->attach($userFirst);
        $anotherChat->users()->attach($userSecond);

        auth()->login($userFirst);

        Livewire::test(ChatSettings::class)
            ->set('selectedChat', $chat)
            ->call('deleteChat');

        $chatsCount = Chat::all()->count();

        $this->assertSoftDeleted($chat);
        $this->assertEquals(1, $chatsCount);
    }
}
