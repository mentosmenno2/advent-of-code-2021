<?php

class Game
{
    /** @var Line[] */
    protected $lines = array();

    protected $grid = array();

    public function __construct( string $data_file )
    {
        $this->load_game_data( $data_file );
        $this->grid = $this->generate_grid();
        $this->start_game();
    }

    protected function get_grid_dimenstions(): array {
        $grid_size = array(
            'min_x' => 0,
            'max_x' => 2,
            'min_y' => 0,
            'max_y' => 5,
        );
        foreach ($this->lines as $line) {
            $grid_size['max_x'] = max( $grid_size['max_x'], $line->get_start_coord()->get_x(), $line->get_end_coord()->get_x() );
            $grid_size['max_y'] = max( $grid_size['max_y'], $line->get_start_coord()->get_y(), $line->get_end_coord()->get_y() );
        }
        return $grid_size;
    }

    protected function generate_grid(): array {
        $grid_size = $this->get_grid_dimenstions();
        $a = array();
        for($x = $grid_size['min_x']; $x < $grid_size['max_x'] + 1; $x++){
            $a[$x] = array();
            for($y = $grid_size['min_y']; $y < $grid_size['max_y'] + 1; $y++){
                $a[$x][$y] = 0;
            }
        }
        return $a;
    }

    protected function load_game_data( string $data_file ): void {
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

    protected function start_game(): void {
        foreach ($this->lines as $line) {
            foreach ($line->get_path() as $coordinate) {
                $this->grid[$coordinate->get_x()][$coordinate->get_y()]++;
            }
        }

        $grid_dimensions = $this->get_grid_dimenstions();
        $two_or_more = 0;
        for ($y=0; $y < $grid_dimensions['max_y'] + 1; $y++) { 
            for ($x=0; $x < $grid_dimensions['max_x'] + 1; $x++) { 
                if ( $this->grid[$x][$y] >= 2 ) {
                    $two_or_more++;
                }
            }
        }

        // $this->display_grid();

        echo sprintf( 'The amount of points where 2 or more lines overlap is %d', $two_or_more );

    }

    /**
     * Display the grid.
     * Do it for fun.
     * Your pc wil like it.
     * Do at your own risk.
     */
    protected function display_grid(): void {
        $grid_dimensions = $this->get_grid_dimenstions();
        for ($y=0; $y < $grid_dimensions['max_y'] + 1; $y++) { 
            for ($x=0; $x < $grid_dimensions['max_x'] + 1; $x++) { 
                var_dump( $this->grid[$x][$y] );
            }
            echo '<br>';
        }
    }
}
