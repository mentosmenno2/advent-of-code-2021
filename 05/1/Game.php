<?php

class Game
{
    /** @var Line[] */
    protected $lines = array();

    public function __construct( string $data_file )
    {
        $this->load_game_data( $data_file );
        $this->start_game();
    }

    protected function load_game_data( string $data_file ) {
        $file_content = file_get_contents( $data_file );
        $file_data = explode( PHP_EOL, $file_content );
        $this->lines = array_map( function( string $line ): Line {
            $line_coords = explode( ' -> ', $line );
            $line_coords = array_map( function( string $coordinate ): Coordinate {
                $coordinate = $coordinate;
                $coordinate = explode( ',', $coordinate );
                $coordinate = array_map( 'intval', $coordinate );
                return new Coordinate( $coordinate[0], $coordinate[1] );
            }, $line_coords );

            return new Line( $line_coords[0], $line_coords[1] );
        }, $file_data );
    }

    protected function start_game() {
        foreach ($this->lines as $line) {
            var_dump( $line );
            echo '<br>';
        }
    }
}
