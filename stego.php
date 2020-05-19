<?php

namespace samxiao\steganography;

const MAGIC_HEAD = 'maS';


class Stego 
{
    protected $img; // Image resource
    protected $cipher;
    protected $cover_width;
    protected $cover_height;
    protected $stego_width;
    protected $stego_height;


    public function __construct()
    {

    }
    
    protected function strToBin($str)
    {
        $str = (string)$str;
        $l = strlen($str);
        $result = '';
        while($l--){
          $result = str_pad(decbin(ord($str[$l])),8,"0",STR_PAD_LEFT).$result;
        }
        return $result;
    }

    protected function binToStr($binary)
    {
        $len = \strlen($binary);
        $startBit = 0;
        for (;$startBit<$len;$startBit++) {
            $bin1 = substr($binary,$startBit,8);
            $char1 = chr(base_convert($bin1,2,10));
            if (strcmp($char1,MAGIC_HEAD[0]) != 0) continue;
            $bin2 = substr($binary,$startBit+8,8);
            $char2 = chr(base_convert($bin2,2,10));
            if (strcmp($char2,MAGIC_HEAD[1]) != 0) continue;
            $bin3 = substr($binary,$startBit+16,8);
            $char3 = chr(base_convert($bin3,2,10));
            if (strcmp($char3,MAGIC_HEAD[2]) == 0) break;
        }

        echo $startBit, PHP_EOL;
        $msg = '';
        $maxChars = 64; // how many characters you wanna show from detected position.
        $end = $startBit + 8 * $maxChars;
        for (;$startBit<$len && $startBit <$end ;$startBit+=64) {
            $foo = substr($binary,$startBit,64);
            $foo = pack('H*',base_convert($foo,2,16));
            $msg .= $foo;
        }
        

        return $msg;
    }

    protected function hideCipher($str)
    {
        $message_to_hide = MAGIC_HEAD.$str;
        $binary_message = $this->strToBin($message_to_hide);
        $message_length = strlen($binary_message);

        $total_pixels = $this->cover_width*$this->cover_height;
        $i = 50000; // From which pixel you wanna hide
        while ($i < $total_pixels) {
            for($j=0;$j<$message_length ;$j++,$i++){    
                if ($i >= $total_pixels) break;       
                $y = intdiv($i, $this->cover_width);
                $x = $i % $this->cover_width;
                $rgb = imagecolorat($this->img,$x,$y);
                $r = ($rgb >>16) & 0xFF;
                $g = ($rgb >>8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $newR = $r;
                $newG = $g;
                $newB = ($b & 0xFE) + (int)$binary_message[$j];
              
                $new_color = imagecolorallocate($this->img,$newR,$newG,$newB);
                imagesetpixel($this->img,$x,$y,$new_color);

            }
            
        }
    }

    protected function showCipher($im)
    {
        $real_message = '';
        $total_pixels = $this->stego_width*$this->stego_height;
        for($i=0;$i<$total_pixels;$i++){
            $y = intdiv($i, $this->stego_width);
            $x = $i % $this->stego_width;
            $rgb = imagecolorat($im,$x,$y);
            $r = ($rgb >>16) & 0xFF;
            $g = ($rgb >>8) & 0xFF;
            $b = $rgb & 0xFF;

            $blue = (string)($b & 1);
            $real_message .= $blue;
        
        }
        $real_message = $this->binToStr($real_message);
        return $real_message;
    } 

    protected function saveImage()
    {
        imagepng($this->img,'simple_sam.png');
        imagedestroy($this->img);
    }

    public function loadImageAndMessage($src, $msg) 
    { 
        $this->img = imagecreatefromjpeg($src);
        $this->cipher = $msg;
        $this->cover_width = imagesx($this->img);
        $this->cover_height = imagesy($this->img);
    }
    
    public function encryptLSB() 
    { 
        $this->hideCipher($this->cipher);
        $this->saveImage();
    
    }

    public function decryptLSB($src) 
    { 
        $im = imagecreatefrompng($src);
        $this->stego_width = imagesx($im);
        $this->stego_height = imagesy($im);
        return $this->showCipher($im);
    
    }
    
}


class Stego2 extends Stego
{




}



function Hello() { return "Hi";}
const MAXSIZE = 65535;




?>