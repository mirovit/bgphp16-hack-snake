<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Game extends Model
{
    protected $fillable = [
        'game_uuid', 'challenger_id', 'winner_id', 'challenged_id', 'finished_at', 'accepted_at',
    ];

    protected $dates = [
        'finished_at', 'accepted_at',
    ];

    protected $appends = [
        'is_finished', 'is_accepted',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Game $model)
        {
            $model->setAttribute('game_uuid', Uuid::uuid1()->toString());
        });
    }

    public function challenger()
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function challenged()
    {
        return $this->belongsTo(User::class, 'challenged_id');
    }

    public function getRouteKeyName()
    {
        return 'game_uuid';
    }

    public function getIsFinishedAttribute()
    {
        return ! is_null($this->finished_at);
    }

    public function getIsAcceptedAttribute()
    {
        return ! is_null($this->accepted_at);
    }

    public function finish($winner_id)
    {
        $this->fill([
            'finished_at' => Carbon::now(),
            'winner_id' => $winner_id,
        ]);

        $this->save();

        return $this;
    }

    public function accepted()
    {
        $this->accepted_at = Carbon::now();
        $this->save();

        return $this;
    }
}
