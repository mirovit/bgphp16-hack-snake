<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Game extends Model
{
    protected $fillable = [
        'game_uuid', 'challenger_id', 'winner_id', 'challenged_id', 'is_accepted',
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
}
