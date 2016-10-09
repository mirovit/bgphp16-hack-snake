<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

use App\Http\Requests;

class PusherController extends Controller
{
    public function  handle(\Illuminate\Http\Request $request){
        $events = collect($request->events);

        $events->each(function(array $event) {
            $data = json_decode($event['data'], true);

            if($event['event'] === 'client-game-over') {
                return $this->handleGameOver($data);
            }

            if($event['event'] === 'client-accepted') {
                $data['game_uuid'] = str_replace('private-waiting-', '', $event['channel']);
                return $this->handleGameAccepted($data);
            }
        });
    }

    protected function handleGameOver(array $data)
    {
        $game = Game::where('game_uuid', $data['game']['game_uuid'])->first();
        $game->finish($data['winner_id']);
    }

    protected function handleGameAccepted(array $data)
    {
        $game = Game::where('game_uuid', $data['game_uuid'])->first();
        $game->accepted();
    }
}
