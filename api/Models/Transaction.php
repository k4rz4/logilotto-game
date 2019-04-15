<?php
	class Transaction extends Model
	{
		private $table = 'Transactions';

		public function createTransaction($client_id, $type, $amount) {
			$this->insert($this->table, ['client_id', 'type', 'amount'], [$client_id, "'".$type."'", "'".$amount."'"]);
			return true;
		}
	}
 ?>
