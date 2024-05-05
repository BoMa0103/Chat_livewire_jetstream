<?php

namespace App\Services\Translations\Clients;

interface TranslationsClient
{
    public function getSupportedLanguages(): array;

    public function translateText(string $content, string $targetLanguageCode): string;
}
