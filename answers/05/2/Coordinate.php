<?php

class Coordinate
{


	/** @var int */
	protected $x;

	/** @var int */
	protected $y;

	public function __construct(int $x, int $y)
	{
		 $this->x = $x;
		$this->y  = $y;
	}

	public function get_x(): int
	{
		return $this->x;
	}

	public function get_y(): int
	{
		return $this->y;
	}
}
