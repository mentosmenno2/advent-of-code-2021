<?php

class Game
{

	/** @var CaveMap */
	protected $map;

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$file_content = explode(PHP_EOL, $file_content);

		$map = array();
		foreach ($file_content as $row_index => $row) {
			$map_row = array();
			foreach (str_split($row) as $column_index => $column_char) {
				$map_row[] = new Point((int)$column_char, $row_index, $column_index);
			}
			$map[] = $map_row;
		}

		$this->map = new CaveMap($map);
	}

	protected function start_game(): void
	{
		$basins = $this->map->get_basins();

		$basin_sizes = array_map('count', $basins);
		rsort($basin_sizes);

		$largest_basin_sizes = array_slice($basin_sizes, 0, 3);
		var_dump(array_product($largest_basin_sizes));
	}
}
