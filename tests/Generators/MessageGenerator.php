<?php

namespace Tests\Generators;

use App\Models\Message;

class MessageGenerator
{
    public static function generate(array $data = [])
    {
        $user = UserGenerator::generate();

        $chat = ChatGenerator::generate();
        $chat->users()->attach($user);

        return Message::factory()->for($user)->for($chat)->create($data);
    }

    public static function generateCollection(int $count)
    {
        $user = UserGenerator::generate();

        $chat = ChatGenerator::generate();
        $chat->users()->attach($user);

        return Message::factory($count)->for($user)->for($chat)->create();
    }

    public static function generateModel(array $data = []): array
    {
        $user = UserGenerator::generate();

        $chat = ChatGenerator::generate();
        $chat->users()->attach($user);

        return [
            'content' => $data['content'] ?? fake()->text,
            'user_id' => $data['user_id'] ?? $user->id,
            'chat_id' => $data['chat_id'] ?? $chat->id,
        ];
    }
}
