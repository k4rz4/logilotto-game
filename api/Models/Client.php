<?php

class Client extends Model
{
	private $table = 'Clients';

	public function getBalance($id) {
		return $this->get($this->table, ['balance'], ['id' => $id])[0]['balance'];
	}

	public function updateBalance($id, $newBalance) {
		$this->update($this->table, ['balance'], [$newBalance], ['id' => $id]);
		return true;
	}

	public function clientExists($id) {
		return $this->getCount($this->table, 'id', ['id' => $id])[0];
	}
}
?>
