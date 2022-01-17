<?php

class Game
{
	protected $lines = array();

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$file_content = explode(PHP_EOL, $file_content);
		$this->lines = $file_content;
	}

	protected function start_game(): void
	{
		$scores = array();
		foreach ($this->lines as $line) {
			$corrupt_char = $this->get_corrupt_char($line);
			if ($corrupt_char) {
				continue;
			}

			$completion_string = $this->get_completion_string($line);
			$scores[] = $this->completion_string_to_autocomplete_score($completion_string);
		}

		var_dump($this->get_autocomplete_winner_from_autocomplete_scores($scores));
	}

	public function get_completion_string(string $line): ?string
	{
		$string_parts = str_split($line);
		$opening_chars = $this->get_opening_chars();
		$bracket_pairs_inverted = array_flip($this->get_bracket_pairs());

		$stack = array();
		foreach ($string_parts as $string_part) {
			if (in_array($string_part, $opening_chars, true)) {
				// Is opening character. Cannot be invalid.
				$stack[] = $string_part;
			} else {
				// Is ending character. Can be invalid.
				$last_stack_char = array_pop($stack);
			}
		}

		$completion_items = array_map(function (string $stack_item) use ($bracket_pairs_inverted) {
			return $bracket_pairs_inverted[$stack_item];
		}, array_reverse($stack));
		return implode('', $completion_items);
	}

	public function get_corrupt_char(string $line): ?string
	{
		$string_parts = str_split($line);
		$opening_chars = $this->get_opening_chars();
		$bracket_pairs = $this->get_bracket_pairs();

		$stack = array();
		foreach ($string_parts as $string_part) {
			if (in_array($string_part, $opening_chars, true)) {
				// Is opening character. Cannot be invalid.
				$stack[] = $string_part;
			} else {
				// Is ending character. Can be invalid.
				$last_stack_char = array_pop($stack);
				if ($last_stack_char !== $bracket_pairs[$string_part]) {
					return $string_part;
				}
			}
		}

		return null;
	}

	protected function get_opening_chars(): array
	{
		return array( '(', '[', '{', '<' );
	}

	protected function get_closing_chars(): array
	{
		return array( ')', ']', '}', '>' );
	}

	protected function get_bracket_pairs(): array
	{
		return array_combine($this->get_closing_chars(), $this->get_opening_chars());
	}

	/**
	 * @param array<string,int>
	 */
	protected function errors_to_syntax_error_score(array $errors): int
	{
		return $errors[')'] * 3 + $errors[']'] * 57 + $errors['}'] * 1197 + $errors['>'] * 25137;
	}

	protected function completion_string_to_autocomplete_score(string $completion_string): int
	{
		$score = 0;
		$completion_string_parts = str_split($completion_string);
		foreach ($completion_string_parts as $char) {
			$score = $score * 5;
			switch ($char) {
				case ')':
					$score += 1;
					break;
				case ']':
					$score += 2;
					break;
				case '}':
					$score += 3;
					break;
				case '>':
					$score += 4;
					break;
			}
		}
		return $score;
	}

	protected function get_autocomplete_winner_from_autocomplete_scores(array $scores): int
	{
		sort($scores);
		$largest_index = count($scores) - 1;
		$middle_index = floor($largest_index / 2);
		return $scores[$middle_index];
	}
}
