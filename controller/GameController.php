<?php

require_once('CardController.php');
require_once('../model/GameManager.php');

session_start();

class GameController
{
	// Game parameters
	const NUMBER_OF_ROWS = 4;
	const NUMBER_OF_COLUMNS = 7;
	// We calculate the number of cards dependent of the number of rows and columns
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
	 * @param mixed $attemp
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
	 * Hydrate the parameters, build the board and put the game in session
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
			$board = [];
			// Build the board in terms of game parameters
			for ($i = 0; $i < $this->getNumberOfCards() ; $i++) {
				$card = $this->getCardInstance($i);
				$board[$i] = $card;
				$board[$this->getNumberOfCards() + $i] = $card;
			}
			$this->setBoard($board);
			$this->setAttempt(0);
			// Function to set the board randomly
			shuffle($this->board);
			$_SESSION['game'] = $this;
		} catch (Exception $e) {
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
			// In the first attemps, we set current index in the previous index
			$this->setPreviousIndex($this->getCurrentIndex());
		}
		$this->setCurrentIndex($index);
		$attempt++;
		if ($attempt == 2) {
			// In the second attempd, we check if the two cards match
			$isMatch = $this->isMatch();
			if ($isMatch) {
				// Update the remaining cards (decrement)
				$this->setRemainingCards($this->getRemainingCards() - 1);
			}
			// Remove the values of attemp, previous and current index
			$this->setPreviousIndex(null);
			$this->setCurrentIndex(null);
			$this->setAttempt(0);
		} else {
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
	 * Checks if two discover cards matched
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
	 * Check if the gamer win the game
	 * @return void
	 */
	public function isSuccessMode()
	{
		// We test if we are in success mode (i.e. the gamer win)
		if (isset($_COOKIE['temps']) && isset($_COOKIE['win'])) {
			if ($_COOKIE['win'] == true) {
				try {
					// Call the GameManager in order to persist and save the data in game table
					$gameManager = new GameManager();
					$gameManager->saveGame();
					// Remove the values of cookies (for manage the previous game without data)
					$_COOKIE['temps'] = '';
					$_COOKIE['win'] = '';
				} catch (Exception $e) {
					echo $e;
				}
			}
		}
	}
}