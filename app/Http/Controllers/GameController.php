<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

use App\Http\Requests;
use Vinkla\Pusher\PusherManager;

class GameController extends Controller
{
    public function showWaitingRoom()
    {
        return view('waiting-room');
    }

    public function showWaitingChallenge($game_uuid)
    {
        $game = Game::with(['challenger', 'challenged'])->where('game_uuid', $game_uuid)->first();

        $challenger = $game->challenger;
        $challenged = $game->challenged;

        return view('challenge-wait', compact('game', 'challenger', 'challenged'));
    }

    public function game($game_uuid)
    {
        $game = Game::with(['challenger', 'challenged'])->where('game_uuid', $game_uuid)->first();

        if($game->is_finished) {
            // TODO show message to user

            return redirect()->route('app.waiting-room');
        }

        $challenger = $game->challenger;
        $challenged = $game->challenged;

        return view('game', compact('game', 'challenger', 'challenged'));
    }

    public function challenge($challenged_id, PusherManager $pusher)
    {
        $challenger = auth()->user();

        $game = Game::create([
            'challenger_id' => $challenger->id,
            'challenged_id' => $challenged_id,
        ]);

        $pusher->trigger("private-challenge-{$challenged_id}", 'challanged-by', ['user' => $challenger, 'game_uuid' => $game->game_uuid]);

        return redirect()->route('app.game.wait', [$game->game_uuid]);
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
