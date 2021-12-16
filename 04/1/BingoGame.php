<?php

class BingoGame
{
    /** @var Board[] */
    protected $boards = array();

    /** @var int[] */
    protected $draw_order = array();

    /** @var int[] */
    protected $drawn_numbers = array();

    public function __construct( string $data_file )
    {
        $this->load_game_data( $data_file );
        $this->start_game();
    }

    protected function load_game_data( string $data_file ) {
        $file_content = file_get_contents( $data_file );
        $file_data = explode( PHP_EOL . PHP_EOL, $file_content );
        $draw_order = array_shift( $file_data );
        $this->draw_order = array_map( 'intval', explode( ',', $draw_order ) );
        $this->boards = array_map( function( string $board ): Board {
            $rows = explode( PHP_EOL, $board );
            foreach ($rows as &$row) {
                $row = array_filter( array_map( 'intval', explode( ' ', $row ) ) );
            }
            return new Board( $rows );
        }, $file_data );
    }

    protected function start_game() {
        $board_that_won = null;
        foreach ($this->draw_order as $drawn_number) {
            $this->drawn_numbers[] = $drawn_number;
            foreach ($this->boards as $board) {
                $board->maybe_mark_square( $drawn_number );
                if ( $board->has_won() ) {
                    $board_that_won = $board;
                    break;
                }
            }

            if ( $board_that_won ) {
                var_dump( $this->calculate_score( $board_that_won, $drawn_number ) );
                break;
            }
        }

        die();
    }

    protected function calculate_score( Board $board, int $last_drawn_number ): int {
        $unmarked_numbers = $board->get_unmarked_numbers();
        $sum = array_sum( $unmarked_numbers );
        return $sum * $last_drawn_number;
    }
}
