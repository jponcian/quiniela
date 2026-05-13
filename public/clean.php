<?php
$src = 'images/trophy.png';
$dest = 'images/trophy_trans.png';

$img = imagecreatefrompng($src);
imagealphablending($img, false);
imagesavealpha($img, true);

// Remove white background (tolerance 240+)
$width = imagesx($img);
$height = imagesy($img);

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        if ($r > 240 && $g > 240 && $b > 240) {
            $transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);
            imagesetpixel($img, $x, $y, $transparent);
        }
    }
}

imagepng($img, $dest);
imagedestroy($img);
echo "Done";
