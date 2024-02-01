<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class GameScore extends Model
{
    protected $connection = 'mongodb';
	protected $collection = 'game_scores';

	public function user()
    {
        return $this->hasOne(\App\Models\User::class,  '_id', 'user_id');
    }

    public function game()
    {
        return $this->hasOne(\App\Models\Game::class,  '_id', 'game_id');
    }
}
