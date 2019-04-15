#! /usr/bin/env php
<?php
echo "Starting...\n";

define('ROOT', '../');

require('../src/core.php');

require('../Models/Bet.php');
require('../Models/Client.php');
require('../Models/Draw.php');
require('../Models/Transaction.php');

$transactions = new Transaction;
$draws = new Draw;
$bets = new Bet;
$clients = new Client;

$sleep = CONFIG['draw_time'];
$odds = CONFIG['odds'];

do {
	try {
		echo "Generating numbers...\n";
		$generatedNumbers = $draws->randomGenerateSevenNumbers();
		echo "Numbers generated " . $generatedNumbers . "\n";
		$draw_id = $draws->storeDrawnNumbers($generatedNumbers);
		echo "Settlement...\n";
		$settlementData = $draws->doSettlement($draw_id);
		if ($settlementData) {
			echo "Creating transactions for won bets!\n";
			echo "Updating bet status\n";
			foreach ($settlementData as $key => $data) {
				if($data['bet_status'] == 'Won') {
					if($transactions->createTransaction($data['client_id'], 'payout',  (float)$data['win_amount'])) {
						$currentBalance = $clients->getBalance($data['client_id']);
						$newBalance = (float)$currentBalance + (float)$data['win_amount'];
						$clients->updateBalance($data['client_id'], $newBalance);
					}
				}
				$bets->updateBetStatus($data['bet_id'], $data['bet_status'],  (float)$data['win_amount']);
				echo "Client_id=".$data['client_id']." | ".$data['bet_status']. " | bet_id=". $data['bet_id']."\n";
			}
		} else {
			echo "No bets for this draw \n";
		}

		sleep($sleep);

	} catch (Exception $e) {
		echo $e;
	}
} while(true);

?>
