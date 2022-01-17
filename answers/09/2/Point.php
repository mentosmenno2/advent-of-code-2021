<?php

class Point
{

	/** @var int */
	protected $height;

	/** @var int */
	protected $row;

	/** @var int */
	protected $column;

	public function __construct(int $height, int $row, int $column)
	{
		$this->height = $height;
		$this->row = $row;
		$this->column = $column;
	}

	public function get_height(): int
	{
		return $this->height;
	}

	public function get_row(): int
	{
		return $this->row;
	}

	public function get_column(): int
	{
		return $this->column;
	}

	public function get_risk_level(): int
	{
		return $this->height + 1;
	}

	/**
	 * @param Point[] $neighbours
	 */
	public function is_low_point_compared_to_neighbours(array $neighbours): bool
	{
		foreach ($neighbours as $neighbour) {
			if ($neighbour->get_height() <= $this->height) {
				return false;
			}
		}
		return true;
	}
}
