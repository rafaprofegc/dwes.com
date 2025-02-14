<?php
$im = imagecreatetruecolor(100, 100);

// sets background to red
$red = imagecolorallocate($im, 255, 0, 0);
imagefill($im, 0, 0, $red);

header('Content-type: image/png');
imagepng($im);
?>