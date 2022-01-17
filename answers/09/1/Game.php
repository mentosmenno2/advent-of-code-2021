<?php

class Game
{

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
		$file_content = array_map(function (string $file_content_line): array {
			return array_map(function (string $line_char) {
				return new Point((int) $line_char);
			}, str_split($file_content_line));
		}, $file_content);
		$this->map = new CaveMap($file_content);
	}

	protected function start_game(): void
	{
		var_dump($this->map->get_risk_level());
	}
}
