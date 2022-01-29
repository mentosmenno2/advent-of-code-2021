<?php

class Game
{
	/** @var array<int,array<int,Octopus>> */
	protected $map = array();

	/** @var int */
	protected $flashes = 0;

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$lines = explode(PHP_EOL, $file_content);
		foreach ($lines as $line_str) {
			$line_arr = str_split($line_str);
			$this->map[] = array_map(function (string $energy_level): Octopus {
				return new Octopus((int) $energy_level);
			}, $line_arr);
		}
	}

	protected function start_game(): void
	{
		for ($k = 0; $k < 100; $k++) {
			$this->do_step();
		}

		var_dump($this->flashes);
	}

	protected function do_step(): void
	{
		$this->increase_energy_levels();
		$this->maybe_flash_octopuses();
		$this->reset_flashed_octopuses();
	}

	protected function increase_energy_levels(): void
	{
		foreach ($this->map as $row_index => $row) {
			foreach ($row as $column_index => $octopus) {
				$this->increase_energy_level($row_index, $column_index);
			}
		}
	}

	protected function increase_energy_level(int $row_index, int $column_index): void
	{
		$octopus = $this->map[$row_index][$column_index];
		$octopus->set_energy_level($octopus->get_energy_level() + 1);
	}

	protected function maybe_flash_octopuses(): void
	{
		foreach ($this->map as $row_index => $row) {
			foreach ($row as $column_index => $octopus) {
				$this->maybe_flash_octopus($row_index, $column_index);
			}
		}
	}

	protected function maybe_flash_octopus(int $row_index, int $column_index): void
	{
		$octopus = $this->map[$row_index][$column_index];
		if ($octopus->get_energy_level() <= 9) {
			return;
		}

		if ($octopus->get_flashed()) {
			return;
		}

		$this->flash_octopus($row_index, $column_index);
	}

	protected function flash_octopus(int $row_index, int $column_index): void
	{
		$octopus = $this->map[$row_index][$column_index];
		$octopus->set_flashed(true);
		$this->flashes++;

		$adjacent_octopusses = array(
			array( $row_index - 1, $column_index ), // Top
			array( $row_index - 1, $column_index + 1 ), // Top right
			array( $row_index, $column_index + 1 ), // Right
			array( $row_index + 1, $column_index + 1 ), // Bottom right
			array( $row_index + 1, $column_index ), // Bottom
			array( $row_index + 1, $column_index - 1 ), // Bottom left
			array( $row_index, $column_index - 1 ), // Left
			array( $row_index - 1, $column_index - 1 ), // Top left
		);

		foreach ($adjacent_octopusses as $indexes) {
			$adjacent_octopus = $this->map[$indexes[0]][$indexes[1]] ?? null;
			if ($adjacent_octopus instanceof Octopus) {
				$this->increase_energy_level($indexes[0], $indexes[1]);
				$this->maybe_flash_octopus($indexes[0], $indexes[1]);
			}
		}
	}

	protected function reset_flashed_octopuses(): void
	{
		foreach ($this->map as $row_index => $row) {
			foreach ($row as $column_index => $octopus) {
				if ($octopus->get_flashed()) {
					$this->map[$row_index][$column_index]->reset();
				}
			}
		}
	}
}
