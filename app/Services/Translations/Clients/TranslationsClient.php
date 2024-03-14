<?php

namespace App\Services\Translations\Clients;

interface TranslationsClient
{
//    public function detectLanguage();
//
    public function getSupportedLanguages();

    public function translateText(string $content, string $targetLanguageCode): string;
}
