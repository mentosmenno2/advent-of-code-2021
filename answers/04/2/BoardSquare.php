<?php

class BoardSquare
{


	protected $number;
	protected $marked = false;

	/**
	 * @param int[] $numbers
	 */
	public function __construct(int $number)
	{
		$this->number = $number;
	}

	public function get_number(): int
	{
		return $this->number;
	}

	public function set_number(int $number): void
	{
		$this->number = $number;
	}

	public function get_marked(): bool
	{
		return $this->marked;
	}

	public function set_marked(bool $marked): void
	{
		$this->marked = $marked;
	}
}
