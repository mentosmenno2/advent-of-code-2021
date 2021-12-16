<?php

class Coordinate
{

    /** @var int */
    protected $x;

    /** @var int */
    protected $y;

    public function __construct( int $x, int $y )
    {
        $this->x = $x;
        $this->y = $y;
    }
}
