<?php

	class Bet extends Model
	{
		private $table = 'Bets';

		public function getBets() {
			return $this->get($this->table, ['id', 'draw_id', 'placed_numbers', 'status', 'win_amount']);
		}

		public function getBetsForSettle($draw_id) {
			return $this->get($this->table, ["*"], ['draw_id' => $draw_id]);
		}

		public function insertBet($stake, $client_id, $draw_id, $placed_numbers) {
			return $this->insert($this->table, ['stake_amount', 'client_id', 'draw_id', 'placed_numbers'], [$stake, $client_id, $draw_id, "'".$placed_numbers."'"]);
		}

		public function updateBetStatus($bet_id, $status, $won) {
			$this->update($this->table, ['status', 'win_amount'], [$status, $won], ['id' => $bet_id]);
			return true;
		}

	}
 ?>
