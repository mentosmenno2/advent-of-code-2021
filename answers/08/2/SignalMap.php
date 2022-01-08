<?php

class SignalMap
{

	protected $number_map = array(
		0 => null,
		1 => null,
		2 => null,
		3 => null,
		4 => null,
		5 => null,
		6 => null,
		7 => null,
		8 => null,
		9 => null,
	);

	public function __construct(array $signal_patterns)
	{
		$this->map_unique_numbers($signal_patterns);
		$this->map_5_digit_numbers($signal_patterns);
		$this->map_6_digit_numbers($signal_patterns);

		foreach ($this->number_map as &$number_map_item) {
			sort($number_map_item);
		}
	}

	/**
	 * Map numbers 1, 4, 7, 8
	 */
	protected function map_unique_numbers(array $signal_patterns)
	{
		foreach ($signal_patterns as $signal_pattern) {
			switch (count($signal_pattern)) {
				case 2:
					$this->number_map[1] = $signal_pattern;
					break;
				case 3:
					$this->number_map[7] = $signal_pattern;
					break;
				case 4:
					$this->number_map[4] = $signal_pattern;
					break;
				case 7:
					$this->number_map[8] = $signal_pattern;
					break;
			}
		}
	}

	/**
	 * Map numbers 2, 3, 5
	 */
	protected function map_5_digit_numbers(array $signal_patterns)
	{
		foreach ($signal_patterns as $signal_pattern) {
			if (count($signal_pattern) !== 5) {
				continue;
			}

			if (count(array_diff($this->number_map[1], $signal_pattern)) === 0) {
				$this->number_map[3] = $signal_pattern;
				continue;
			}

			if (count(array_diff($this->number_map[4], $signal_pattern)) === 2) {
				$this->number_map[2] = $signal_pattern;
				continue;
			}

			$this->number_map[5] = $signal_pattern;
		}
	}

	/**
	 * Map numbers 0, 6, 9
	 */
	protected function map_6_digit_numbers(array $signal_patterns)
	{
		foreach ($signal_patterns as $signal_pattern) {
			if (count($signal_pattern) !== 6) {
				continue;
			}

			if (count(array_diff($this->number_map[1], $signal_pattern)) === 1) {
				$this->number_map[6] = $signal_pattern;
				continue;
			}

			if (count(array_diff($this->number_map[4], $signal_pattern)) === 0) {
				$this->number_map[9] = $signal_pattern;
				continue;
			}

			$this->number_map[0] = $signal_pattern;
		}
	}

	public function get_map(): array
	{
		return $this->number_map;
	}
}
