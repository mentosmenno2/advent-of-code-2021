<?php

class CaveMap
{

	/** @var array<int, Point>[] */
	protected $map;

	public function __construct(array $map)
	{
		$this->map = $map;
	}

	public function get_risk_level(): int
	{
		$risk_level = 0;
		foreach ($this->get_lowest_points() as $point) {
			$risk_level += $point->get_risk_level();
		}
		return $risk_level;
	}

	protected function get_lowest_points(): array
	{
		$lowest_points = array();
		foreach ($this->map as $row_index => $row) {
			foreach ($row as $column_index => $point) {
				$neighbours = $this->get_neighbours($row_index, $column_index);
				if ($point->is_low_point_compared_to_neighbours($neighbours)) {
					$lowest_points[] = $point;
				}
			}
		}

		return $lowest_points;
	}

	/**
	 * @return Point[]
	 */
	protected function get_neighbours(int $row_index, int $column_index): array
	{
		$neighbours = array(
			$this->map[$row_index - 1][$column_index] ?? null,
			$this->map[$row_index][$column_index + 1] ?? null,
			$this->map[$row_index + 1][$column_index] ?? null,
			$this->map[$row_index][$column_index - 1] ?? null,
		);

		return array_filter($neighbours, function ($neighbour): bool {
			return $neighbour !== null;
		});
	}
}
