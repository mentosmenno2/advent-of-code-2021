<?php

class Game
{
	/** @var array<string,array<int,string>> */
	protected $cave_connections = array();

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
			$line_caves = explode( '-', $line );
			$cave_a = $line_caves[0];
			$cave_b = $line_caves[1];
			if ( ! array_key_exists( $cave_a, $this->cave_connections ) ) {
				$this->cave_connections[$cave_a] = array();
			}
			if ( ! array_key_exists( $cave_b, $this->cave_connections ) ) {
				$this->cave_connections[$cave_b] = array();
			}
			if ( ! in_array( $cave_a, $this->cave_connections[$cave_b] ) ) {
				$this->cave_connections[$cave_b][] = $cave_a;
			}
			if ( ! in_array( $cave_b, $this->cave_connections[$cave_a] ) ) {
				$this->cave_connections[$cave_a][] = $cave_b;
			}
		}
	}

	protected function start_game(): void
	{
		
	}
}
