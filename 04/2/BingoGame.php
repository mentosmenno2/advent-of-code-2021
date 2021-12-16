<?php

class BingoGame
{
    /** @var Board[] */
    protected $boards = array();

    /** @var int[] */
    protected $draw_order = array();

    /** @var bool */
    protected $get_winning_board = true;

    public function __construct( string $data_file, bool $get_winning_board = true )
    {
        $this->get_winning_board = $get_winning_board;
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
                $row = trim( $row );
                $row = str_replace( '  ', ' ', $row );
                $row = explode( ' ', $row );
                $row = array_map( 'intval', $row );
                $row = array_values( $row );
            }

            return new Board( $rows );
        }, $file_data );
    }

    protected function start_game() {
        $last_won_board = null;
        $last_drawn_number = null;
        foreach ($this->draw_order as $drawn_number) {
            foreach ($this->boards as $board_index => $board) {
                if ( $board->has_won() ) {
                    continue;
                }

                $board->maybe_mark_square( $drawn_number );
                if ( $board->has_won() ) {
                    $last_won_board = $board;
                    $last_drawn_number = $drawn_number;
                }
            }

            if ( $this->get_winning_board && $last_won_board ) {
                var_dump( $this->calculate_score( $last_won_board, $drawn_number ) );
                break;
            }
        }

        if ( ! $this->get_winning_board ) {
            var_dump( $this->calculate_score( $last_won_board, $last_drawn_number ) );
        }
    }

    protected function calculate_score( Board $board, int $last_drawn_number ): int {
        $unmarked_numbers = $board->get_unmarked_numbers();
        $sum = array_sum( $unmarked_numbers );
        return $sum * $last_drawn_number;
    }
}
