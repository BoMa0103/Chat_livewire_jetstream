<?php

namespace Tests\Generators;

use App\Models\Chat;

class ChatGenerator
{
    public static function generate(array $data = []): Chat
    {
        return Chat::factory()->create($data);
    }

    public static function generateModel(array $data = []): array
    {
        return [
            'name' => $data['name'] ?? fake()->name,
            'chat_type' => $data['chat_type'] ?? Chat::CONVERSATION,
        ];
    }
}
