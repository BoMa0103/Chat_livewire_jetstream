<?php

namespace App\Services\Translations;

use App\Services\Translations\Clients\TranslationsClient;

class TranslationsService
{
    public function __construct(
        private readonly TranslationsClient $translationsClient,
    )
    {
    }

    public function getSupportedLanguages(): array
    {
        return $this->translationsClient->getSupportedLanguages();
    }

    public function translateText(string $content, string $targetLanguageCode): string
    {
        return $this->translationsClient->translateText($content, $targetLanguageCode);
    }
}
