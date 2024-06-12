<?php

namespace App\Providers;

use App\Services\Chats\Repositories\ChatRepository;
use App\Services\Chats\Repositories\EloquentChatRepository;
use App\Services\Messages\Repositories\EloquentMessageRepository;
use App\Services\Messages\Repositories\MessageRepository;
use App\Services\Translations\Clients\GoogleCloud\GoogleCloudTranslationsClient;
use App\Services\Translations\Clients\TranslationsClient;
use App\Services\Users\Repositories\EloquentUserRepository;
use App\Services\Users\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChatRepository::class, EloquentChatRepository::class);
        $this->app->bind(MessageRepository::class, EloquentMessageRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);

        $this->app->bind(TranslationsClient::class, GoogleCloudTranslationsClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
