<?php
// We include this classes to call usefull methods we will find below
require_once('CardController.php');
require_once('../model/GameManager.php');

// We use this function to start a session
// In this way, we can store the game in session to process the game
session_start();

/**
 * Initialize the game parameters, build the board game and the game logic
 */
class GameController
{
	// Game parameters
	const NUMBER_OF_ROWS = 4;
	const NUMBER_OF_COLUMNS = 7;
	// We calculate the number of cards dependent on the number of rows and columns
	const NUMBER_OF_CARDS = (self::NUMBER_OF_ROWS * self::NUMBER_OF_COLUMNS) / 2;

	private $board;
	private $numberOfCards;
	private $numberOfRows;
	private $numberOfColumns;
	private $remainingCards;
	private $attempt;
	private $previousIndex;
	private $currentIndex;

	/**
	 * Set the current index of the card
	 * @param mixed $currentIndex
	 */
	public function setCurrentIndex($currentIndex)
	{
		$this->currentIndex = $currentIndex;
	}

	/**
	 * Get the current index of the card
	 * @return mixed
	 */
	public function getCurrentIndex()
	{
		return $this->currentIndex;
	}

	/**
	 * Set the previous index of the card
	 * @param mixed $previousIndex
	 */
	public function setPreviousIndex($previousIndex)
	{
		$this->previousIndex = $previousIndex;
	}

	/**
	 * Get the previous index of the card
	 * @return mixed
	 */
	public function getPreviousIndex()
	{
		return $this->previousIndex;
	}

	/**
	 * Set the attempt
	 * @param mixed $attempt
	 */
	public function setAttempt($attempt)
	{
		$this->attempt = $attempt;
	}

	/**
	 * Get the attempt
	 * @return mixed
	 */
	public function getAttempt()
	{
		return $this->attempt;
	}

	/**
	 * Set the remaining cards
	 * @param mixed $remainingCards
	 */
	public function setRemainingCards($remainingCards)
	{
		$this->remainingCards = $remainingCards;
	}

	/**
	 * Get the remaining cards
	 * @return mixed
	 */
	public function getRemainingCards()
	{
		return $this->remainingCards;
	}

	/**
	 * Set the number of cards
	 * @param mixed $numberOfCards
	 */
	public function setNumberOfCards($numberOfCards)
	{
		$this->numberOfCards = $numberOfCards;
	}

	/**
	 * Get the number of cards
	 * @return mixed
	 */
	public function getNumberOfCards()
	{
		return $this->numberOfCards;
	}

	/**
	 * Set the number of columns
	 * @param mixed $numberOfColumns
	 */
	public function setNumberOfColumns($numberOfColumns)
	{
		$this->numberOfColumns = $numberOfColumns;
	}

	/**
	 * Get the number of columns
	 * @return mixed
	 */
	public function getNumberOfColumns()
	{
		return $this->numberOfColumns;
	}

	/**
	 * Set the number of rows
	 * @param mixed $numberOfRows
	 */
	public function setNumberOfRows($numberOfRows)
	{
		$this->numberOfRows = $numberOfRows;
	}

	/**
	 * Get the number of rows
	 * @return mixed
	 */
	public function getNumberOfRows()
	{
		return $this->numberOfRows;
	}

	/**
	 * Set the board
	 * @param mixed $board
	 */
	public function setBoard($board)
	{
		$this->board = $board;
	}

	/**
	 * Get the board
	 * @return mixed
	 */
	public function getBoard()
	{
		return $this->board;
	}

	/**
	 * Get a card
	 * @param $index
	 * @return Card
	 */
	public function getCardInstance($index)
	{
		return new Card($index);
	}

	/**
	 * Constructor
	 * Hydrate the parameters, build the board and store the game in session
	 */
	public function __construct()
	{
		// We use a try / catch in order to catch the potential error
		try {
			// Set parameters with constant class
			$this->setNumberOfCards(self::NUMBER_OF_CARDS);
			$this->setNumberOfRows(self::NUMBER_OF_ROWS);
			$this->setNumberOfColumns(self::NUMBER_OF_COLUMNS);
			$this->setRemainingCards($this->getNumberOfCards());
			// Initialize an empty array for the board
			$board = [];
			// Build the board depending on the game parameters
			// We loop on the cards
			for ($i = 0; $i < $this->getNumberOfCards() ; $i++) {
				// We retrieve a card instance
				$card = $this->getCardInstance($i);
				// We store the card in the board
				$board[$i] = $card;
				$board[$this->getNumberOfCards() + $i] = $card;
			}
			// We set the board and the attempt
			$this->setBoard($board);
			$this->setAttempt(0);
			// Function to set the board randomly (randomizes the order of the cards)
			shuffle($this->board);
			// Store the game in session
			$_SESSION['game'] = $this;
		} catch (Exception $e) {
			// We catch the error
			die('Error ' . $e->getMessage());
		}
	}

	/**
	 * Discover a card according to a given index
	 * @param $index
	 * @return array
	 */
	public function discoverCard($index)
	{
		$response = [];
		$board = $this->getBoard();
		$attempt = $this->getAttempt();
		$isMatch = false;
		if ($attempt == 0) {
			$this->setPreviousIndex($index);
		} else if ($attempt == 1) {
			// In the first attempt, we set current index in the previous index
			// (in order to compare the two discovered cards)
			$this->setPreviousIndex($this->getCurrentIndex());
		}
		$this->setCurrentIndex($index);
		$attempt++;
		if ($attempt == 2) {
			// In the second attempt, we check if the two cards match
			$isMatch = $this->isMatch();
			if ($isMatch) {
				// Update the remaining cards (decrement)
				$this->setRemainingCards($this->getRemainingCards() - 1);
			}
			// Remove the values of previous and current index, and set the value of the attempt to zero
			$this->setPreviousIndex(null);
			$this->setCurrentIndex(null);
			$this->setAttempt(0);
		} else {
			// For cases where the attempt is equal to 0 or 1, we set the value of attempt
			$this->setAttempt($attempt);
		}
		// Update the game in session
		$_SESSION['game'] = $this;
		// Set the response with current data
		$response['attempt'] = $this->getAttempt();
		$response['isMatch'] = $isMatch;
		$response['currentImage'] = $board[$index]->getImage();
		$response['remainingCards'] = $this->getRemainingCards();
		return $response;
	}

	/**
	 * Check if two discovered cards match
	 * @return bool
	 */
	private function isMatch()
	{
		// Get the board, the previous and the current indexes
		$board = $this->getBoard();
		$previousIndex = $this->getPreviousIndex();
		$currentIndex = $this->getCurrentIndex();
		// If the previous and current images are the same, then return true (else return false)
		return ($board[$previousIndex]->getImage() == $board[$currentIndex]->getImage());
	}

	/**
	 * Check if the player wins the game
	 * @return void
	 */
	public function isSuccessMode()
	{
		// We test if we are in success mode (i.e. the player wins)
		if (isset($_COOKIE['temps']) && isset($_COOKIE['win'])) {
			if ($_COOKIE['win'] == true) {
				try {
					// Call the GameManager in order to persist and save the data in game table
					$gameManager = new GameManager();
					$gameManager->saveGame();
					// Remove the values of cookies (to manage the next game without data)
					$_COOKIE['temps'] = '';
					$_COOKIE['win'] = '';
				} catch (Exception $e) {
					echo $e;
				}
			}
		}
	}
}