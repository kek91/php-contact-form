<?php 
///////////////////////////////////////////////////////////////////
//                                                                /
//    ASCII PHP Captcha                                           /
//    Author: http://phpsnips.com/71/ASCII-CAPTCHA                /
//    Modified by Teknix / Kim Eirik Kvassheim <kek@teknix.no>    /
//                                                                /
///////////////////////////////////////////////////////////////////


$string = md5(rand(0,5000)); 
$string = substr($string,0,5); 
$letters = str_split($string); 

$iw = 160;  // image width
$ih = 50;   // image height
$fs = 24;   // font size
$image = imagecreatetruecolor($iw,$ih); 
$white = imagecolorallocate($image, 255, 255, 255); 
$black = imagecolorallocate($image, 0, 0, 0); 
imagefilledrectangle($image, 0, 0, $iw, $ih, $white); // Background

for($i=0; $i<500; $i++) // Static noise: dots
{
    @imagesetpixel($image, rand(1,160), rand(1,50), imagecolorallocate($image, rand(1,250), rand(1,250), rand(1,250)));   
}
for($i=0; $i<50; $i++) // Static noise: lines
{
    imageline($image, rand(1,$iw), rand(1,$ih), rand(1,$iw), rand(1,$ih), imagecolorallocate($image, rand(170,250), rand(170,250), rand(170,250))); 
}

imagerectangle($image, 0, 0, $iw-1, $ih-1, $black); // Border

$y_min = ($ih / 2) + ($fs / 3) - 2; // Set minimum letter y position 
$y_max = ($ih / 2) + ($fs / 3) + 2; // Set maximum letter y position 
$x = $fs / 2; // Set the letter starting point 

$i = 0; 
foreach($letters as $letter){ 
    // Set the angle of the letter random from -45 and 45 
    $angle = rand(-30, 30); 
    // Set a random y position of the letter using using the above min and max 
    $y = rand($y_min, $y_max); 
    // check to see if this is first letter, yes then skip next line otherwise, 
    // add font size to x to move letter to the right 
    if($i != 0) $x += $fs;
    $font_color = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200)); 
    imagettftext($image, $fs, $angle, $x, $y, $font_color, 'bebas.ttf', $letter); 
    // Incrament $i 
    $i++; 
} 
// Finally build the image! 
// Build the ASCII image 
echo '<div id="captcha" style="line-height:1px; font-size:1px;">'; 
for($h=0;$h<$ih;$h++){ 
    for($w=0;$w<=$iw;$w++){ 
        $rgb = imagecolorat($image, $w, $h); 
        $r = ($rgb >> 16) & 0xFF; 
        $g = ($rgb >> 8) & 0xFF; 
        $b = $rgb & 0xFF; 
        if($w == $iw){ 
            echo '<br />'; 
        }else{ 
            echo '<span style="color:rgb('.$r.','.$g.','.$b.');">#</span>'; 
        } 
    } 
} 
echo '</div>'; 
//End ASCII Image Build 
$_SESSION['CAPTCHA'] = $string; 
?> 

<p>
    Image String: <?php echo $_SESSION['CAPTCHA']; ?> 
</p>
