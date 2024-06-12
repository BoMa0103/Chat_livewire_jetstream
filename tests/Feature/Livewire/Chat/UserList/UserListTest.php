<?php

namespace Tests\Feature\Livewire\Chat\UserList;

use App\Livewire\Chat\UserList\UserList;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Generators\ChatGenerator;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckChatAndCreateNew(): void
    {
        $userFirst = UserGenerator::generate();
        $userSecond = UserGenerator::generate();

        Livewire::actingAs($userFirst);

        Livewire::test(UserList::class)
            ->call('checkChat', $userSecond->id);

        $chat = Chat::all()->first();
        $chatsCount = Chat::all()->count();

        $this->assertSame($userFirst->id, ($chat->users()->get()->find($userFirst->id))->id);
        $this->assertSame($userSecond->id, ($chat->users()->get()->find($userSecond->id))->id);
        $this->assertEquals(1, $chatsCount);
    }

    public function testCheckChatThatAlreadyExists(): void
    {
        $chat = ChatGenerator::generate();

        $userFirst = UserGenerator::generate();
        $userSecond = UserGenerator::generate();

        $chat->users()->attach($userFirst);
        $chat->users()->attach($userSecond);

        Livewire::actingAs($userFirst);

        Livewire::test(UserList::class)
            ->call('checkChat', $userSecond->id);

        $chat = Chat::all()->first();
        $chatsCount = Chat::all()->count();

        $this->assertSame($userFirst->id, ($chat->users()->get()->find($userFirst->id))->id);
        $this->assertSame($userSecond->id, ($chat->users()->get()->find($userSecond->id))->id);
        $this->assertEquals(1, $chatsCount);
    }
}
