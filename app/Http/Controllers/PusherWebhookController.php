<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusherWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = json_decode($request->getContent());

        dump($payload);
    }
}
