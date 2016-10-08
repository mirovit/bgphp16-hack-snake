<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Vinkla\Pusher\PusherManager;

class GameController extends Controller
{
    public function showWaitingRoom()
    {
        return view('waiting-room');
    }

    public function game()
    {
        return view('game');
    }

    public function challenge(User $user, PusherManager $pusher)
    {
        $pusher->trigger("challange-{$user->id}", 'challanged-by', ['user' => auth()->user()]);
    }

    public function userCheck(Request $request, PusherManager $pusher)
    {
        $user = auth()->user();

        if(!$user) {
            return response('Forbidden', 403);
        }

        $pusherData = $pusher->presence_auth($request->channel_name, $request->socket_id, $user->id, [
            'name' => $user->name,
            'avatar' => $user->avatar,
        ]);

        return response($pusherData);
    }
}
