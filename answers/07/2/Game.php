<?php

class Game
{
	/** @var int[] */
	protected $positions = array();

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$file_data    = explode(',', $file_content);
		$this->positions = array_map('intval', $file_data);
	}

	protected function start_game(): void
	{
		$min = min($this->positions);
		$max = max($this->positions);

		$cheapest_position = null;
		$least_fuel = PHP_INT_MAX;
		for ($test_position=$min; $test_position < $max + 1; $test_position++) {
			$test_fuel = 0;
			foreach ($this->positions as $position) {
				$distance_between = abs($position - $test_position);
				$fuel_usage = 0;
				for ($step=1; $step < $distance_between + 1; $step++) {
					$fuel_usage += $step;
				}
				$test_fuel += $fuel_usage;

				if ($test_position === 5) {
					echo sprintf('Move from %d, to %d: %d fuel.' . PHP_EOL, $position, $test_position, $fuel_usage);
				}
			}

			if ($test_fuel < $least_fuel) {
				$least_fuel = $test_fuel;
				$cheapest_position = $test_position;
			}
		}

		echo sprintf('The cheapest position is %d, costing %d fuel.', $cheapest_position, $least_fuel);
	}
}
