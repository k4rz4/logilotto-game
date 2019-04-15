<?php

class Draw extends Model
{
	private $table = 'Draws';

	public function gatDraws()
	{
		return $this->get($this->table, ['*']);
	}

	public function getDrawById($draw_id)
	{
		return $this->get($this->table, ['*'], ['id' => $draw_id])[0];
	}

	public function getNextDrawId()
	{
		$lastDrawId = $this->get('INFORMATION_SCHEMA.TABLES', ['AUTO_INCREMENT'], ['TABLE_SCHEMA' => "'".CONFIG['db_database']."'", 'TABLE_NAME' => "'".$this->table."'"])[0]['AUTO_INCREMENT'];
		return ((int)$lastDrawId);
	}

	public function randomGenerateSevenNumbers()
	{
		$random_number_array = range(1, 60);
		shuffle($random_number_array);
		$random_number_array = array_slice($random_number_array, 0, 7);

		return implode(',', $random_number_array);
	}

	public function storeDrawnNumbers($draw_numbers)
	{
		return $this->insert($this->table, ['draw_numbers'], ["'".$draw_numbers."'"]);
	}

	public function doSettlement($draw_id)
	{
		$response = [];
		$bets = new Bet;
		$settleBets = $bets->getBetsForSettle($draw_id);

		if ($settleBets != null) {
			$drawNum = explode(",", $this->getDrawById($draw_id)['draw_numbers']);

			foreach ($settleBets as $key => $settleBet) {
				$betStatus = [];
				$bNum = explode(",", $settleBet['placed_numbers']);
				$winOdds = $this->calculateWin($bNum, $drawNum, explode(",", CONFIG['odds']));
				if (!$winOdds) {
					$status = "Lost";
					$betStatus['win_amount'] = 0;
				} else {
					$status = 'Won';
					$betStatus['win_amount'] = (float)$settleBet['stake_amount']*(float)$winOdds;
				}

				$betStatus['client_id'] = $settleBet['client_id'];
				$betStatus['bet_status'] = $status;
				$betStatus['bet_id'] = $settleBet['id'];
				$response[] = $betStatus;
			}
			return $response;
		} else {
			return false;
		}
	}

	public function calculateWin($bet_numbers, $draw_numbers, $odds)
	{
		$numOfMatched = array_intersect($bet_numbers, $draw_numbers);
		if (count($numOfMatched) > 0) {
			return $odds[count($numOfMatched)-1];
		} else {
			return false;
		}
	}
}
