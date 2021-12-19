<?php

class Game
{

	/** @var LanternFish[] */
	protected $fish = array();

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
		$this->fish  = array_map(
			function (string $internal_fish_timer): LanternFish {
				return new LanternFish((int) $internal_fish_timer);
			},
			$file_data
		);
	}

	protected function start_game(): void
	{
		// $this->display_fish_timers(0);

		for ($day=1; $day < $this->days_to_simulate + 1; $day++) {
			foreach ($this->fish as $fish) {
				if ($fish->can_give_birth()) {
					$this->fish[] = new LanternFish();
				}
				$fish->pass_internal_timer_day();
			}

			// $this->display_fish_timers($day);
		}

		echo sprintf('<strong>After %d days the total amount of fish is: %s </strong>', $this->days_to_simulate, count($this->fish));
	}

	protected function display_fish_timers(int $day): void
	{
		$fish_timers = array_map(function (LanternFish $fish): int {
			return $fish->get_internal_timer();
		}, $this->fish);
		echo sprintf('After %d days: %s <br>', $day, implode(',', $fish_timers));
	}
}
