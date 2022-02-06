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

		foreach ($this->cave_paths as $cave_path) {
			echo implode(',', $cave_path) . '<br>';
		}

		echo sprintf('Wow! The found amount of routes through all these caves is an amazing %d routes!', count($this->cave_paths));
	}

	/**
	 * @param array $cave_path array( 'start' )
	 * @return void
	 */
	protected function go_in_cave(array $cave_path)
	{
		$cave = $cave_path[count($cave_path) - 1];
		if ($cave === 'end') {
			$this->cave_paths[] = $cave_path;
			return;
		}

		foreach ($this->cave_connections[$cave] as $cave_option) {
			if (strtolower($cave_option) === $cave_option && in_array($cave_option, $cave_path, true)) { // Is small cave and already in path
				continue;
			}

			$new_cave_path = $cave_path;
			$new_cave_path[] = $cave_option;
			$this->go_in_cave($new_cave_path);
		}
	}
}
