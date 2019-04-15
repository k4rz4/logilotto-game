<?php
require(ROOT . 'Models/Bet.php');
require(ROOT . 'Models/Client.php');
require(ROOT . 'Models/Draw.php');
require(ROOT . 'Models/Transaction.php');

class MainController extends Controller
{
	public function getBets() {
		$response = [];
		$bets = new Bet();
		$draw = new Draw();
		$draw_id = $draw->getNextDrawId()-1;
		$nextDraw = $draw->getDrawById($draw_id);
		$betsAll = $bets->getBets();
		$timeLeft = $this->calculateTime($nextDraw['draw_date']);
	  $nextDraw['time'] = $timeLeft;

		foreach ($betsAll as $key => $bet) {
			$res = array_slice($bet, 0, 3, true) +
	    array("draw_numbers" => $draw->getDrawById($bet['draw_id'])['draw_numbers']) +
	    array_slice($bet, 3, count($bet) - 1, true) ;

			$response['bets'][$key] = $res;
		}

		$response['nextDrawPull'] = $nextDraw;
		$this->jsonSuccess($response);
  }

  public function placeBet($request) {
  	$request = $request->getBody();
  	if (!array_key_exists("client_id", $request) || !array_key_exists("stake", $request) || !array_key_exists("placed_numbers", $request)) {
  		$this->jsonError("Some of data missing");
  	}

  	$client = new Client;
		$draw 	= new Draw;
		$transaction 	= new Transaction;
		$bet = new Bet;

		if($client->clientExists($request->client_id) == 0) {
			$this->jsonError("Client with id: ".$request->client_id." doesn't exist");
		}
		$balance = (float)$client->getBalance($request->client_id);

		if ( $balance < $request->stake) {
			$this->jsonError('Not enough balance');
		}
		if (!$this->validateNumbers($request->placed_numbers)) {
			$this->jsonError('Numbers must be between 1 and 60');
		}

		$draw_id = $draw->getNextDrawId();
		$bet_id = $bet->insertBet((float)$request->stake, (int)$request->client_id, (int)$draw_id, $request->placed_numbers);

		$newBalance = $balance - (float)$request->stake;
		$transaction->createTransaction($request->client_id, 'payment', $request->stake);
		$client->updateBalance($request->client_id, $newBalance);

		$response = [
				'bet_id' => $bet_id,
				'draw_id' => $draw_id,
				'bet_numbers' => $request->placed_numbers,
				'draw_numbers' => 'pending',
				'status' => 'pending',
				'win_amount'	=> 'pending',
				'balance' => number_format($newBalance,2)
 			];

  	$this->jsonSuccess($response);
  }

  public function validateClient($request)
  {
  	$response = [];
  	$request = $request->getBody();
  	$client = new Client;
  	$exists = $client->clientExists($request->client_id);
  	if ($exists > 0) {
  		$response['client_status'] = true;
  		$balance = $client->getBalance($request->client_id);
  		$response['balance'] =  number_format($balance,2);
  	} else {
  		$response['client_status'] = false;
  	}
  	$this->jsonSuccess($response);
  }

  private function validateNumbers($numbersString)
	{
		$numbers = explode(',', $numbersString);
		$atLeastOne = 0;
		foreach ($numbers as $key => $number) {
			if (!$this->testRange($number)) {
				$atLeastOne++;
			}
		}
		return ($atLeastOne > 0) ? false : true;
	}

	public function testRange($int,$min=0,$max=60)
	{
    return ($min <= $int && $int <= $max) ? true : false;
	}

	public function calculateTime ($date)
	{
		$difference_in_seconds = strtotime($date)-time()+CONFIG['draw_time'];
		return $difference_in_seconds;
	}
}
?>
