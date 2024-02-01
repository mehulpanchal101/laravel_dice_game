<?php

namespace App\Exports;

use App\Models\GameScore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;

class ScoreExport implements FromArray
{
	protected $score;

	public function __construct(array $game_scores)
	{
		$this->score = $game_scores;
	}

	public function array(): array
	{
		return $this->score;
	}
}
