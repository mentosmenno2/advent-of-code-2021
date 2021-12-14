<?php

// Read data from file, and prepare it for use
$file_content = file_get_contents( 'data.txt' );
$report_lines = explode( PHP_EOL, $file_content );
$report_lines = array_map( function( string $report_line ): array {
    $report_line = str_split( $report_line );
    $report_line = array_map( 'intval', $report_line );
    return $report_line;
}, $report_lines );

/**
 * Every row of data consists of 12 bytes.
 * Therefore we can loop over those 12 bytes, and get all bytes in every column.
 * 
 * Calculating if there are more ones or zero's in the column can be done in multiple ways.
 * I've chosen to get the sum of all values in a colum, and devide it by the amount of lines in the data.
 * If that is larger than 0.5, there are more ones.
 * If that is smaller than 0.5, there are more zeroes.
 */
$gamma_rate_binary = '';
$epsilon_rate_binary = '';
for ( $k = 0 ; $k < 12; $k++ ){
    $column_bits = array_column( $report_lines, $k );
    $column_sum = array_sum( $column_bits );
    $is_one_common = ( $column_sum / count( $column_bits ) ) > 0.5;

    $gamma_rate_binary .= $is_one_common ? '1' : '0';
    $epsilon_rate_binary .= $is_one_common ? '0' : '1';
}

$gamma_rate = (int) bindec( $gamma_rate_binary );
$epsilon_rate = (int) bindec( $epsilon_rate_binary );
$power_consumption = $gamma_rate * $epsilon_rate;

var_dump( $power_consumption );