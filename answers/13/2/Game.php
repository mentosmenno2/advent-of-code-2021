<?php

class Game
{

	protected $paper = array();
	protected $fold_instructions = array();

	public function __construct(string $data_file)
	{
		$this->load_game_data($data_file);
		$this->start_game();
	}

	protected function load_game_data(string $data_file): void
	{
		$file_content = file_get_contents($data_file);
		$parts = explode(PHP_EOL . PHP_EOL, $file_content);
		$paper = $parts[0];
		$raw_data_rows = explode(PHP_EOL, $paper);
		$x_s = array();
		$y_s = array();
		foreach ($raw_data_rows as $value) {
			$coordinate = explode(',', $value);
			$x_s[] = (int) $coordinate[0];
			$y_s[] = (int) $coordinate[1];
		}

		$max_x = max($x_s);
		$max_y = max($y_s);
		$this->paper = array_fill(0, $max_x + 1, array_fill(0, $max_y + 1, '.'));
		foreach ($x_s as $index => $x) {
			$this->paper[$x][$y_s[$index]] = '#';
		}

		$fold_instructions = $parts[1];
		$this->fold_instructions = array_map(function (string $fold_instruction_row) {
			$parts = explode('=', str_replace('fold along ', '', $fold_instruction_row));
			return array( 'direction' => $parts[0], 'index' => (int) $parts[1] );
		}, explode(PHP_EOL, $fold_instructions));
	}

	protected function start_game(): void
	{
		foreach ($this->fold_instructions as $fold_instruction) {
			if ($fold_instruction['direction'] === 'y') {
				$this->paper = $this->fold_y($this->paper, $fold_instruction['index']);
			} else {
				$this->paper = $this->fold_x($this->paper, $fold_instruction['index']);
			}
		}
		$this->print_x_y_map($this->paper);
	}

	protected function fold_y(array $paper, int $fold_index)
	{
		foreach ($paper as $x => $ys) {
			foreach ($ys as $y => $char) {
				if ($y === $fold_index) {
					break;
				}

				$length_below_fold = $fold_index - $y;
				$opposite_y = $fold_index + $length_below_fold;
				$opposite_coordinate = $paper[$x][$opposite_y] ?? null;
				if ($opposite_coordinate && $opposite_coordinate === '#') {
					$paper[$x][$y] = $opposite_coordinate;
				}
			}
		}

		foreach ($paper as $x => $ys) {
			foreach ($ys as $y => $char) {
				if ($y >= $fold_index) {
					unset($paper[$x][$y]);
				}
			}
		}
		return $paper;
	}

	protected function fold_x(array $paper, int $fold_index)
	{
		foreach ($paper as $x => $ys) {
			if ($x === $fold_index) {
				break;
			}
			$length_below_fold = $fold_index - $x;
			$opposite_x = $fold_index + $length_below_fold;

			foreach ($ys as $y => $char) {
				$opposite_coordinate = $paper[$opposite_x][$y] ?? null;
				if ($opposite_coordinate && $opposite_coordinate === '#') {
					$paper[$x][$y] = $opposite_coordinate;
				}
			}
		}

		foreach ($paper as $x => $ys) {
			if ($x >= $fold_index) {
				unset($paper[$x]);
			}
		}
		return $paper;
	}

	protected function print_x_y_map(array $map): void
	{
		foreach ($this->flip_row_col_array($map) as $row => $row_items) {
			foreach ($row_items as $column => $char) {
				echo $char;
			}
			echo PHP_EOL;
		}
		echo PHP_EOL;
	}

	protected function flip_row_col_array(array $array)
	{
		$out = array();
		foreach ($array as $rowkey => $row) {
			foreach ($row as $colkey => $col) {
				$out[$colkey][$rowkey]=$col;
			}
		}
		return $out;
	}
}
