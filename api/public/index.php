<?php
try {
	header('Content-Type: application/json');
 	header("Access-Control-Allow-Origin: *");

	define('WEBROOT', str_replace("public/index.php", "", $_SERVER["SCRIPT_NAME"]));
	define('ROOT', str_replace("public/index.php", "", $_SERVER["SCRIPT_FILENAME"]));

	require(ROOT . 'src/core.php');

	require(ROOT . 'Http/Router.php');
	require(ROOT . 'Http/IRequest.php');
	require(ROOT . 'Http/Request.php');
	require(ROOT . 'Http/Dispatcher.php');
	$dispatch = new Dispatcher();
	$dispatch->dispatch();

} catch (Exception $e) {
	header('HTTP/1.1 500 Internal Server Error');
	print_r($e->getMessage());
	echo json_encode(["status" => 500, "Error" => $e->getMessage()]);
	die();
}

?>
