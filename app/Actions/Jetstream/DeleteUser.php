<?php

namespace App\Actions\Jetstream;

use App\Events\UserDelete;
use App\Models\User;
use App\Services\Chats\ChatsService;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    private function getChatsService(): ChatsService
    {
        return app(ChatsService::class);
    }
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        $chats = $user->chats()->get();

        $deletedUserId = $user->id;

        $user->email = $user->email . uniqid();
        $user->save();
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();

        foreach ($chats as $chat) {
            $receivers = $this->getChatsService()->getChatReceivers($chat->id, $deletedUserId)->get();
            foreach ($receivers as $receiver) {
                broadcast(new UserDelete($receiver->id, $deletedUserId));
            }
        }
    }
}
