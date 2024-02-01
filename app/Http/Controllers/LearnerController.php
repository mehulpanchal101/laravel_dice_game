<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LearnerController extends Controller
{
	public function show()
	{
		$games = \App\Models\GameScore::with('user','game')->get();
		return response()->json(["result" => $games], 201);		
	}
}
