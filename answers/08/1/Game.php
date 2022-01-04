<?php

class Game
{

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
	}

	protected function start_game(): void
	{
		
	}
}
