<?php

class Board
{

    /** @var array[] */
    protected $squares_rows = array();

    /**
     * @param array[] $numbers Arrays of rows, where each row is an array of integers
     */
    public function __construct( array $number_rows )
    {
        $this->squares_rows = array_map( function( array $number_row ): array {
            return array_map( function( int $number ): BoardSquare {
                return new BoardSquare( $number );
            }, $number_row );
        }, $number_rows );
    }

    public function maybe_mark_square( int $number ): void {
        foreach ($this->squares_rows as $square_row_index => $square_row) {
            foreach ($square_row as $square_index => $square) {
                if ( $square->get_number() === $number ) {
                    $this->squares_rows[$square_row_index][$square_index]->set_marked( true );
                    return;
                }
            }
        }
    }

    public function has_won(): bool {
        return $this->has_won_horizontal() || $this->has_won_vertical();
    }

    protected function has_won_horizontal(): bool {
        foreach ($this->squares_rows as $square_row) {
            $marked_squares = array_filter( $square_row, function( BoardSquare $square ): bool {
                return $square->get_marked();
            } );
            if ( count( $marked_squares ) === 5 ) {
                return true;
            }
        }
        return false;
    }

    protected function has_won_vertical(): bool {
        $squares_in_row = count( $this->squares_rows[0] );
        for ($i=0; $i < $squares_in_row; $i++) { 
            $marked_squares = array_filter( array_column( $this->squares_rows, $i ), function( BoardSquare $square ): bool {
                return $square->get_marked();
            } );
            if ( count( $marked_squares ) === 5 ) {
                return true;
            }
        }
        return false;
    }

    public function get_unmarked_numbers(): array {
        $unmarked_numbers = array();
        foreach ($this->squares_rows as $square_row) {
            foreach ($square_row as $square) {
                if ( ! $square->get_marked() ) {
                    $unmarked_numbers[] = $square->get_number();
                }
            }
        }
        return $unmarked_numbers;
    }
}
