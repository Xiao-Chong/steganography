<?php

use function samxiao\steganography\{Hello};
use const samxiao\steganography\{MAXSIZE};

require_once __DIR__.'/stego.php';

$x = 9;
$y = 9 >> 1;
var_dump($y);
var_dump(base_convert((string)$x,10,2));
$z = $y << 1;
var_dump($z);
var_dump($x >> 1 << 1);

echo MAXSIZE, PHP_EOL;
echo Hello();

?>