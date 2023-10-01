<?php

namespace App\Livewire\Validators;

class HtmlValidator
{
    public function customHtmlspecialcharsForImg($message)
    {
        $imgTags = [];

        if($message === null){
            return null;
        }


        $content = preg_replace_callback('/<img[^>]*>/', function($matches) use (&$imgTags) {
            $imgTags[] = $matches[0];
            return '###IMG###';
        }, $message->content);

        $content = htmlspecialchars($content);

        $content = nl2br($content);

        foreach ($imgTags as $imgTag) {
            $content = preg_replace('/###IMG###/', $imgTag, $content, 1);
        }

        return $content;
    }
}
