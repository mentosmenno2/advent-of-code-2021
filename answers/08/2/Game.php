<?php

class Game
{

	protected $entries = array();

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$file_content = explode(PHP_EOL, $file_content);
		$file_content = array_map(function (string $entry): array {
			$entry_parts = explode(' | ', $entry);
			return array(
				'signal_patterns' => array_map('str_split', explode(' ', $entry_parts[0])),
				'output_value' => array_map('str_split', explode(' ', $entry_parts[1])),
			);
		}, $file_content);
		$this->entries = $file_content;
	}

	protected function start_game(): void
	{
		$total = 0;

		foreach ($this->entries as $entry) {
			$map = ( new SignalMap($entry['signal_patterns']) )->get_map();

			$output_number = '';
			foreach ($entry['output_value'] as &$output_value) {
				sort($output_value);
				$output_number .= (string) array_search($output_value, $map, true);
			}

			echo $output_number . '<br>';

			$total += (int) $output_number;
		}

		echo sprintf('Total: %d', $total);
	}
}
