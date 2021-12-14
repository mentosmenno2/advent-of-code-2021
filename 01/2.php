<?php

// Read data from file, and prepare it for use
$file_content = file_get_contents( 'data.txt' );
$depths = explode( PHP_EOL, $file_content );
$depths = array_map( 'intval', $depths );

$previous_sum = null;
$count = 0;
foreach ($depths as $index => $depth) {
    $data = array_filter( array(
        $depths[$index-1] ?? null,
        $depths[$index] ?? null,
        $depths[$index+1] ?? null,
    ) );

    if ( count( $data ) < 3 ) {
        continue;
    }

    $sum = array_sum( $data );
    if ( $previous_sum === null ) {
        $previous_sum = $sum;
        continue;
    }

    if ( $sum > $previous_sum ) {
        $count++;
    }
    
    $previous_sum = $sum;
}

var_dump( $count );
