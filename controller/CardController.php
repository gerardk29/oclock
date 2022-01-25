<?php

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

	public function __construct($index)
	{
		$cardImages = [
			'1.jpg', '2.jpg', '3.jpg', '4.jpg',
			'5.jpg', '6.jpg', '7.jpg', '8.jpg',
			'9.jpg', '10.jpg', '11.jpg', '12.jpg',
            '13.jpg', '14.jpg'
		];
		if (!isset($cardImages[$index])) {
			throw new Exception( $index . " n'est pas une carte valide");
		}
		$this->setImage($cardImages[$index]);
	}
}