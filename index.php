<?php
	$answers_dir = './answers/';
	$dirs = array_diff(scandir($answers_dir), array('..', '.'));
	$dirs = array_filter( $dirs, function( $dir ) use( $answers_dir ) {
		return is_dir( $answers_dir . $dir );
	} );

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Advent of Code 2021</title>
    </head>
    <body>
		<h1>Advent of Code 2021</h1>
		<div class="sites">
			<?php foreach( $dirs as $dir ) { ?>
				<div><a title="Day <?php echo $dir; ?>" href="<?php echo $answers_dir . $dir; ?>">Day <?php echo $dir; ?></a></div>
			<?php } ?>
		</div>
    </body>
</html>
