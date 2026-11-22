<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Generate a simple PNG data URI for the given string.
     * Uses GD to draw the string; for production replace with proper QR library.
     */
    public function generate(string $data): string
    {
        $width = 200;
        $height = 200;
        $im = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $fg = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, $width, $height, $bg);
        // Simple placeholder: render the raw data as text
        imagestring($im, 5, 10, $height / 2 - 10, $data, $fg);
        ob_start();
        imagepng($im);
        $png = ob_get_clean();
        imagedestroy($im);
        return 'data:image/png;base64,' . base64_encode($png);
    }
}
