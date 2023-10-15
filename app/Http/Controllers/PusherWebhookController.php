<?php

namespace App\Http\Controllers;

use App\Events\MarkAsOffline;
use Illuminate\Http\Request;

class PusherWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
//        $payload = json_decode($request->getContent());

        if ($request['events'][0]['name'] === 'channel_vacated' && explode('.', $request['events'][0]['channel'])[0] === 'online') {
            broadcast(event: new MarkAsOffline(
                $request,
            ));
        }
    }
}
