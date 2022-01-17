<?php

class CaveMap
{

	/** @var array<int, Point>[] */
	protected $map;

	public function __construct(array $map)
	{
		$this->map = $map;
	}

	/**
	 * @return array<int,Point>[]
	 */
	public function get_basins(): array
	{
		$basins = array();
		foreach ($this->get_lowest_points() as $lowest_point) {
			$is_in_basin = false;
			foreach ($basins as $basin) {
				if (in_array($lowest_point, $basin)) {
					$is_in_basin;
					break;
				}
			}

			if ($is_in_basin) {
				continue;
			}
			$basins[] = $this->get_basin(array( $lowest_point ));
		}

		return $basins;
	}

	/**
	 * @param Point[] $points
	 */
	protected function get_basin(array $points)
	{
		$points_in_basin = $points;
		foreach ($points as $point) {
			$neighbours = $this->get_neighbours($point->get_row(), $point->get_column());
			foreach ($neighbours as $neighbour) {
				if ($neighbour->get_height() === 9) {
					continue;
				}

				if (! in_array($neighbour, $points_in_basin, true)) {
					$points_in_basin[] = $neighbour;
				}
			}
		}

		if (count($points_in_basin) !== count($points)) {
			$points_in_basin = $this->get_basin($points_in_basin);
		}
		return $points_in_basin;
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
