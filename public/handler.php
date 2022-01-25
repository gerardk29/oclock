<?php
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

header('Content-Type: application/json');
$handler = new Handler();

// Manage the case where the card index does not exist
if (!isset($_GET['index'])) {
	$handler->error = 1;
	$handler->message = 'carte non valide';
	echo json_encode($response);
	exit();
}

$result = $handler->game->uncoverCard($_GET['index']);
echo json_encode($result);