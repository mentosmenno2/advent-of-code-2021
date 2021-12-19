<?php

// Read data from file, and prepare it for use
$file_content = file_get_contents('data.txt');
$depths       = explode(PHP_EOL, $file_content);
$depths       = array_map('intval', $depths);

$previous_depth = null;
$count          = 0;
foreach ($depths as $depth) {
	if ($previous_depth === null) {
		$previous_depth = $depth;
		continue;
	}

	if ($depth > $previous_depth) {
		$count++;
	}
	$previous_depth = $depth;
}

var_dump($count);
