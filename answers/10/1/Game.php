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
		$errors = array_fill_keys($this->get_closing_chars(), 0);
		foreach ($this->lines as $line) {
			$errorring_char = $this->get_erroring_char($line);
			if ($errorring_char) {
				$errors[$errorring_char]++;
			}
		}

		var_dump($this->errors_to_syntax_error_score($errors));
	}

	public function get_erroring_char(string $line): ?string
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
}
