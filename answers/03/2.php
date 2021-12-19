<?php

// phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

// Read data from file, and prepare it for use
$file_content = file_get_contents('data.txt');
$report_lines = explode(PHP_EOL, $file_content);
$report_lines = array_map(
	function (string $report_line): array {
		$report_line = str_split($report_line);
		$report_line = array_map('intval', $report_line);
		return $report_line;
	},
	$report_lines
);

$life_support_rating = calculate_rating_from_report_lines($report_lines, true) * calculate_rating_from_report_lines($report_lines, false);
var_dump($life_support_rating);

function calculate_rating_from_report_lines(array $report_lines, bool $oxygen)
{
	for ($k = 0; $k < 12; $k++) {
		$column_bits   = array_column($report_lines, $k);
		$is_one_common = is_one_common_in_column($column_bits, true);

		$report_lines = array_filter(
			$report_lines,
			function (array $report_line_bits) use ($k, $is_one_common, $oxygen): bool {
				$allow = $report_line_bits[ $k ] === ( $is_one_common ? 1 : 0 );
				if (! $oxygen) {
					return ! $allow;
				}
				return $allow;
			}
		);
		$report_lines = array_values($report_lines);

		if (count($report_lines) === 1) {
			break;
		}
	}

	$oxygen_generator_rating_binary = (int) implode('', $report_lines[0]);
	return (int) bindec($oxygen_generator_rating_binary);
}

/**
 * Check if 1 is more common in the array of bits than a 0.
 * If both are equally common, $equal_is_one_common determines if 1 is more common than 0.
 *
 * @param int[] $column_bits
 * @param boolean $equal_is_one_common
 * @return boolean
 */
function is_one_common_in_column(array $column_bits, bool $equal_is_one_common = true): bool
{
	$column_sum = array_sum($column_bits);
	$average    = ( $column_sum / count($column_bits) );
	if ($average === 0.5) {
		return $equal_is_one_common ? true : false;
	}

	return $average > 0.5;
}
