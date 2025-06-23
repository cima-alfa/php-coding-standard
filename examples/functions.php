<?php

$x = function ($x, $y) {
	echo $x + $y;
};

$x = function ($y) use ($x) {
	echo $x + $y;
};

$x = fn($x, $y) => $x + $y;
