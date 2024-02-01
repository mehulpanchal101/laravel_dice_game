<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameScoreRequest;
use PDF;
use App\Exports\ScoreExport;
use Maatwebsite\Excel\Facades\Excel;

class GameController extends Controller
{
	public function get_game()
	{
		$games = \App\Models\Game::first();
		return response()->json(["result" => $games], 200);		
	}

	public function show()
	{
		$score = \App\Models\GameScore::with('user','game')->orderBy('updated_at','desc')->get();
		return response()->json(["result" => $score], 200);		
	}

	public function store(GameScoreRequest $request)
	{
		$game_score = new \App\Models\GameScore;
		$game_score->game_id = $request->game_id;
		$game_score->user_id = $request->user_id;
		$game_score->score = $request->score;
		$game_score->time_taken = $request->time_taken;
		$game_score->save();
		return response()->json(["result" => "Score saved"], 201);
	}

	public function export_to_pdf(){
		$score = \App\Models\GameScore::with('user','game')->orderBy('updated_at','desc')->get();
		$pdf = PDF::loadView('pdf/pdf',compact('score'));
		return $pdf->download('score_list.pdf');
	}

	public function export_to_excel(){
		$score = \App\Models\GameScore::with('user','game')->orderBy('updated_at','desc')->get();
		$exportdata = [];
		array_push($exportdata,array('Nickname','Score','Time Taken'));
		foreach ($score as $key => $value) {
			array_push($exportdata, array($value->user->nickname ,$value->score,$value->time_taken ));
		}
		$export = new ScoreExport($exportdata);
		return Excel::download($export, 'score_list.xlsx');
	}
}
