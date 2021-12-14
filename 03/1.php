<?php

$file_content = file_get_contents( 'data.txt' );
$report_lines = explode( PHP_EOL, $file_content );
$report_lines = array_map( function( string $report_line ): array {
    $report_line = str_split( $report_line );
    $report_line = array_map( 'intval', $report_line );
    return $report_line;
}, $report_lines );

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