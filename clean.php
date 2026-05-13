<?php
$src = 'public/images/trophy.png';
$dest = 'public/images/trophy.png';

// The file is actually a JPEG
$img = imagecreatefromjpeg($src);

// Create a new true color image with alpha channel
$width = imagesx($img);
$height = imagesy($img);
$newImg = imagecreatetruecolor($width, $height);

// Make the new image background transparent
imagesavealpha($newImg, true);
$transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
imagefill($newImg, 0, 0, $transparent);

// Copy pixels, making white (or near white) transparent
for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // If it's near white, skip (leave transparent)
        if ($r > 230 && $g > 230 && $b > 230) {
            continue;
        }
        
        // Otherwise, copy the pixel
        $color = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
        imagesetpixel($newImg, $x, $y, $color);
    }
}

// Save as true PNG, overwriting the old one
imagepng($newImg, $dest);

imagedestroy($img);
imagedestroy($newImg);
echo "Background removed and saved as transparent PNG.\n";
