<?php

namespace App\Http\Controllers;

use App\Events\MarkAsOffline;
use Illuminate\Http\Request;

class PusherWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        // @todo test
        $payload = json_decode($request->getContent());

        broadcast(event: new MarkAsOffline(
            1,
            $payload,
        ));
    }
}
