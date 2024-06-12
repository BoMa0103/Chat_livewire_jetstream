<?php

namespace Tests\Feature\Livewire\Chat\ChatBox;

use App\Livewire\Chat\ChatBox\SendMessage;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Generators\ChatGenerator;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    use RefreshDatabase;

    public function testSendMessage()
    {
        $chat = ChatGenerator::generate();

        $userFirst = UserGenerator::generate();
        $userSecond = UserGenerator::generate();

        $chat->users()->attach($userFirst);
        $chat->users()->attach($userSecond);

        Livewire::actingAs($userFirst);

        $content = fake()->text;

        Livewire::test(SendMessage::class)
            ->set('selectedChat', $chat)
            ->set('body', $content)
            ->call('sendMessage');

        $message = Message::all()->first();
        $messagesCount = Message::all()->count();

        $this->assertSame($content, $message->content);
        $this->assertEquals(1, $messagesCount);
    }
}
