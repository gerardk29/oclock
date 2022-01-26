<?php
// We include this class to call usefull methods we will find below
require_once('../controller/GameController.php');

class Handler
{
	public $error = 0;
	public $message = "";
	public $game;

	/**
	* Hydrate the game by session
	*/
	public function __construct()
	{
		$this->game = $_SESSION['game'];
	}
}

// We specifiy that the header will contains a JSON data
header('Content-Type: application/json');

$handler = new Handler();

// Manage the case where the card index does not exist
// If the index is not set, we set the attributes error and message and display the error
if (!isset($_GET['index'])) {
	$handler->error = 1;
	$handler->message = 'carte non valide';
	echo json_encode($response);
	exit();
}
// Call to the GameController discoverCard method to update the game
$result = $handler->game->discoverCard($_GET['index']);
echo json_encode($result);