<?php

namespace App\Livewire\Validators;

use App\Models\Message;

class HtmlValidator
{
    public function customHtmlspecialcharsForImg(string $message): string
    {
        $imgTags = [];

        $message = preg_replace_callback('/<img[^>]*>/', function($matches) use (&$imgTags) {
            if (str_contains($matches[0], 'class="joypixels"')) {
                $imgTags[] = $matches[0];
                return '###IMG###';
            } else {
                return $matches[0];
            }
        }, $message);

        $message = htmlspecialchars($message);

        $message = nl2br($message);

        foreach ($imgTags as $imgTag) {
            $message = preg_replace('/###IMG###/', $imgTag, $message, 1);
        }

        return $message;
    }
}
