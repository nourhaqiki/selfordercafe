<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Generate a simple SVG-based barcode data URI.
     * Uses inline SVG instead of GD — no image library needed, much lighter.
     */
    public function generate(string $data): string
    {
        $encoded = htmlspecialchars($data, ENT_XML1, 'UTF-8');
        $bars = $this->textToBars($data);
        $barWidth = 2;
        $svgWidth = count($bars) * $barWidth;
        $svgHeight = 80;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $svgWidth . '" height="' . ($svgHeight + 20) . '" viewBox="0 0 ' . $svgWidth . ' ' . ($svgHeight + 20) . '">';
        $svg .= '<rect width="100%" height="100%" fill="white"/>';

        $x = 0;
        foreach ($bars as $bar) {
            if ($bar) {
                $svg .= '<rect x="' . $x . '" y="0" width="' . $barWidth . '" height="' . $svgHeight . '" fill="black"/>';
            }
            $x += $barWidth;
        }

        $svg .= '<text x="' . ($svgWidth / 2) . '" y="' . ($svgHeight + 14) . '" text-anchor="middle" font-family="monospace" font-size="11" fill="black">' . $encoded . '</text>';
        $svg .= '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function textToBars(string $text): array
    {
        $bars = [];
        foreach (str_split($text) as $char) {
            $val = ord($char);
            for ($i = 7; $i >= 0; $i--) {
                $bars[] = ($val >> $i) & 1;
            }
            $bars[] = 0; // separator
        }
        return $bars;
    }
}
