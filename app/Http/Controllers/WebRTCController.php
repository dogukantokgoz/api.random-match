<?php

namespace App\Http\Controllers;

use App\Events\VoiceCallEvent;
use Illuminate\Http\Request;

class WebRTCController extends Controller
{
    public function signal(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'data' => 'required',
            'to_user_id' => 'required|exists:users,id',
        ]);

        broadcast(new VoiceCallEvent(
            $request->type,
            $request->data,
            $request->user()->id,
            $request->to_user_id
        ));

        return response()->json(['message' => 'Signal sent successfully']);
    }
}
