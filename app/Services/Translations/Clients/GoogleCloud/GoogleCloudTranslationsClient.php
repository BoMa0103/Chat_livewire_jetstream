<?php

namespace App\Services\Translations\Clients\GoogleCloud;

use App\Services\Translations\Clients\TranslationsClient;
use Google\Client as GoogleClient;
use GuzzleHttp\Client;

class GoogleCloudTranslationsClient implements TranslationsClient
{
    private $httpClient;
    private $googleClient;

    public function __construct()
    {
        $this->googleClient = new GoogleClient();
        $this->httpClient = new Client();
    }

    public function getSupportedLanguages(): array
    {
        $url = 'https://translate.googleapis.com/v3/projects/35602587281/supportedLanguages';
        $this->googleClient->setAuthConfig(storage_path('app/citric-glow-348722-63bad30fad01.json'));
        $this->googleClient->addScope('https://www.googleapis.com/auth/cloud-translation');
        $this->httpClient= $this->googleClient->authorize($this->httpClient);

        $response = $this->httpClient->get($url, [
            'query' => [
                'displayLanguageCode' => 'en',
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $languages = collect($data['languages']);

        return $languages->map(function ($language) {
            return [
                'code' => $language['languageCode'],
                'name' => $language['displayName'],
            ];
        })->toArray();
    }

    public function translateText(string $content, string $targetLanguageCode): string
    {
        $url = 'https://translate.googleapis.com/v3/projects/35602587281:translateText';
        $this->googleClient->setAuthConfig(storage_path('app/citric-glow-348722-63bad30fad01.json'));
        $this->googleClient->addScope('https://www.googleapis.com/auth/cloud-translation');
        $this->httpClient= $this->googleClient->authorize($this->httpClient);

        $response = $this->httpClient->post($url, [
            'json' => [
                'contents' => [
                    $content
                ],
                'targetLanguageCode' => $targetLanguageCode,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['translations'][0]['translatedText'] ?? $content;
    }
}
