<?php

class Line
{

    /** @var Coordinate */
    protected $start_coord;

    /** @var Coordinate */
    protected $end_coord;

    /**
     * @param int[] $numbers
     */
    public function __construct( Coordinate $start, Coordinate $end )
    {
        $this->start_coord = $start;
        $this->end_coord = $end;
    }
}
