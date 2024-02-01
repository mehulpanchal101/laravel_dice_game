<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        \App\Models\Game::truncate();
        \App\Models\Game::create([
    		'name' => "Dice Game",
    		'round_of_play' => 3
    	]);
        \App\Models\User::truncate();
        \App\Models\User::create([
            'email' => 'learner@dicegame.com',
            'nickname' => "Learner",
            'role' => 'learner',
            'password' => Hash::make('123456789'),
        ]);
        \App\Models\User::create([
            'email' => 'administrator@dicegame.com',
            'nickname' => 'Admin',
            'role' => isset($role) ? $role : 'administrator',
            'password' => Hash::make('administrator'),
        ]);
        \App\Models\GameScore::truncate();
    }
}
