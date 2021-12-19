<?php

require_once 'BoardSquare.php';
require_once 'Board.php';
require_once 'BingoGame.php';

$game_winning_board = new BingoGame(__DIR__ . '/../data.txt', true);

$game_losing_board = new BingoGame(__DIR__ . '/../data.txt', false);
