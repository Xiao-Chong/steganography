<?php

//use samxiao\steganography\{Stego};
use samxiao\steganography\{Stego2};

require_once __DIR__.'/stego.php';

/*
$foo = new Stego();
$foo->loadImageAndMessage('1.jpg','Sam N988');
$foo->encryptLSB();
$msg = $foo->decryptLSB('simple_sam.png');
echo $msg,PHP_EOL;
*/
$bar = new Stego2();

echo $bar->decryptLSB('simple_sam.png');


?>