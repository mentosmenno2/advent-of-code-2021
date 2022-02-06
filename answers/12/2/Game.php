<?php

class Game
{
	/** @var array<string,array<int,string>> */
	protected $cave_connections = array();

	/** @var array */
	protected $cave_paths = array();

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$lines = explode(PHP_EOL, $file_content);
		foreach ($lines as $line) {
			$line_caves = explode('-', $line);
			$cave_a = $line_caves[0];
			$cave_b = $line_caves[1];
			if (! array_key_exists($cave_a, $this->cave_connections)) {
				$this->cave_connections[$cave_a] = array();
			}
			if (! array_key_exists($cave_b, $this->cave_connections)) {
				$this->cave_connections[$cave_b] = array();
			}
			if (! in_array($cave_a, $this->cave_connections[$cave_b])) {
				$this->cave_connections[$cave_b][] = $cave_a;
			}
			if (! in_array($cave_b, $this->cave_connections[$cave_a])) {
				$this->cave_connections[$cave_a][] = $cave_b;
			}
		}
	}

	protected function start_game(): void
	{
		$starting_cave = 'start';
		$this->go_in_cave(array( $starting_cave ));
		echo sprintf('Wow! The found amount of routes through all these caves is an amazing %d routes!', count($this->cave_paths));
	}

	protected function go_in_cave(array $cave_path)
	{
		$cave = $cave_path[count($cave_path) - 1];
		if ($cave === 'end') {
			$this->cave_paths[] = $cave_path;
			return;
		}

		foreach ($this->cave_connections[$cave] as $cave_option) {
			if (! $this->can_go_in_cave($cave_option, $cave_path)) { // Is small cave and already in path
				continue;
			}

			$new_cave_path = $cave_path;
			$new_cave_path[] = $cave_option;
			$this->go_in_cave($new_cave_path);
		}
	}

	protected function can_go_in_cave(string $cave, array $cave_path): bool
	{
		// Always allow small caves
		if (strtoupper($cave) === $cave) {
			return true;
		}

		// Dont allow starting cave
		if ($cave === 'start') {
			return false;
		}

		// If not visited before, always allow
		if (! in_array($cave, $cave_path, true)) {
			return true;
		}

		// Allow if never visited a small cave twice before
		$lowercase_caves = array_filter($cave_path, function ($cave) {
			return strtolower($cave) === $cave;
		});
		if (count($lowercase_caves) === count(array_flip($lowercase_caves))) {
			return true;
		}

		return false;
	}
}
