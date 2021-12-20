<?php

class Game
{

	/** @var int[] */
	protected $fish_per_time = array();

	protected $days_to_simulate;

	public function __construct(string $data_file, int $days_to_simulate = 80)
	{
		$this->load_game_data($data_file);
		$this->days_to_simulate = $days_to_simulate;
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$file_data    = explode(',', $file_content);
		$file_data  = array_map('intval', $file_data);
		$this->fish_per_time = array_fill(0, 9, 0);

		foreach ($file_data as $fish_timer) {
			$this->fish_per_time[$fish_timer]++;
		}
	}

	protected function start_game(): void
	{
		$days_to_simulate = $this->days_to_simulate + 1;
		for ($day=1; $day < $days_to_simulate; $day++) {
			$fish_at_zero = array_shift($this->fish_per_time);
			$this->fish_per_time = array_values($this->fish_per_time);
			$this->fish_per_time[8] = $fish_at_zero;
			$this->fish_per_time[6] += $fish_at_zero;
		}

		echo sprintf('<strong>After %d days the total amount of fish is: %s</strong>', $this->days_to_simulate, array_sum($this->fish_per_time));
	}
}
