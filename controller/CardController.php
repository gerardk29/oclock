<?php

/**
 * Prepare the card's images used to build the game
 */
class Card
{
	private $image;

	/**
	 * Set image
	 * @param mixed $image
	 */
	public function setImage($image)
	{
		$this->image = $image;
	}

	/**
	 * Get image
	 * @return mixed
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * Set the board images
	 * Check if each image index is valid
	 */
	public function __construct($index)
	{
		// Prepare the images used to build the board
		$cardImages = [
			'1.jpg', '2.jpg', '3.jpg', '4.jpg',
			'5.jpg', '6.jpg', '7.jpg', '8.jpg',
			'9.jpg', '10.jpg', '11.jpg', '12.jpg',
            '13.jpg', '14.jpg'
		];
		// If the index does not exist, throw an error
		if (!isset($cardImages[$index])) {
			throw new Exception( $index . " n'est pas une carte valide");
		}
		// Set images
		$this->setImage($cardImages[$index]);
	}
}