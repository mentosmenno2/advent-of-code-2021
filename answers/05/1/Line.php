<?php

class Line
{


	/** @var Coordinate */
	protected $start_coord;

	/** @var Coordinate */
	protected $end_coord;

	/**
	 * @param int[] $numbers
	 */
	public function __construct(Coordinate $start, Coordinate $end)
	{
		 $this->start_coord = $start;
		$this->end_coord    = $end;
	}

	public function get_start_coord(): Coordinate
	{
		return $this->start_coord;
	}

	public function get_end_coord(): Coordinate
	{
		return $this->end_coord;
	}

	/**
	 * @return Coordinate[]
	 */
	public function get_path(): array
	{
		$coords = array();
		if ($this->is_horizontal() || $this->is_vertical()) {
			$start_x = $this->start_coord->get_x() < $this->end_coord->get_x() ? $this->start_coord->get_x() : $this->end_coord->get_x();
			$end_x   = $this->start_coord->get_x() > $this->end_coord->get_x() ? $this->start_coord->get_x() : $this->end_coord->get_x();

			$start_y = $this->start_coord->get_y() < $this->end_coord->get_y() ? $this->start_coord->get_y() : $this->end_coord->get_y();
			$end_y   = $this->start_coord->get_y() > $this->end_coord->get_y() ? $this->start_coord->get_y() : $this->end_coord->get_y();

			for ($x = $start_x; $x < $end_x + 1; $x++) {
				for ($y = $start_y; $y < $end_y + 1; $y++) {
					$coords[] = new Coordinate($x, $y);
				}
			}
		}
		return $coords;
	}

	protected function is_horizontal(): bool
	{
		return $this->start_coord->get_y() === $this->end_coord->get_y();
	}

	protected function is_vertical(): bool
	{
		return $this->start_coord->get_x() === $this->end_coord->get_x();
	}
}
