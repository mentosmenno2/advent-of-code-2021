<?php

// Read data from file, and prepare it for use
$file_content = file_get_contents( 'data.txt' );
$instructions = explode( PHP_EOL, $file_content );
$instructions = array_map( function( $instruction ): array {
    $instruction = explode( ' ', $instruction );
    $instruction[1] = intval( $instruction[1] );
    return $instruction;
}, $instructions );

$total_x = 0;
$total_depth = 0;
foreach ($instructions as $instruction) {
    switch ($instruction[0]) {
        case 'forward':
            $total_x += $instruction[1];
            break;
        case 'down':
            $total_depth += $instruction[1];
            break;
        case 'up':
            $total_depth -= $instruction[1];
            break;
        default:
            die( 'Defuq happened here?' );
            break;
    }
}

var_dump( $total_x * $total_depth );