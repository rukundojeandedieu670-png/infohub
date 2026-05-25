<?php
// generate_favicon_ico.php
// Generates a single-image favicon.ico (PNG inside ICO) from a simple GD-rendered image.
// Usage: php generate_favicon_ico.php
// Requires PHP with GD enabled.

$sizes = [64]; // single-size ICO; change/add sizes if needed
$outFile = __DIR__ . '/favicon.ico';

// Create PNG image for the first (and only) size using GD
$size = $sizes[0];
$im = imagecreatetruecolor($size, $size);
imagesavealpha($im, true);
$bg = imagecolorallocate($im, 22, 163, 74); // #16a34a
$white = imagecolorallocate($im, 255, 255, 255);
imagefilledrectangle($im, 0, 0, $size, $size, $bg);

// Draw a simple 'I' shape in white
$barW = (int)($size * 0.18);
$barH = (int)($size * 0.6);
$cx = (int)($size / 2);
$cyTop = (int)($size * 0.2);
$cy = $cyTop + (int)($barH / 2);
imagefilledrectangle($im, $cx - (int)($barW/2), $cyTop, $cx + (int)($barW/2), $cyTop + $barH, $white);
// Draw top and bottom horizontal caps
$capH = (int)($size * 0.08);
imagefilledrectangle($im, (int)($size*0.15), $cyTop - $capH, (int)($size*0.85), $cyTop, $white);
imagefilledrectangle($im, (int)($size*0.15), $cyTop + $barH, (int)($size*0.85), $cyTop + $barH + $capH, $white);

// Output PNG to memory
ob_start();
imagepng($im);
$png = ob_get_clean();
imagedestroy($im);

// Build ICO file with a single PNG image (supported by modern Windows/Chrome)
$count = 1;
$iconDir = pack('vvv', 0, 1, $count);
$entryOffset = 6 + 16 * $count;
$bytesInRes = strlen($png);
$widthByte = $size >= 256 ? 0 : $size; // 0 means 256 in ICO spec
$heightByte = $widthByte;
$dirEntry = pack('C C C C v v V V', $widthByte, $heightByte, 0, 0, 0, 0, $bytesInRes, $entryOffset);

$ico = $iconDir . $dirEntry . $png;

if (file_put_contents($outFile, $ico) !== false) {
    echo "Wrote $outFile\n";
    exit(0);
} else {
    fwrite(STDERR, "Failed to write $outFile\n");
    exit(1);
}
