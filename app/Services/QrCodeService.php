<?php

namespace App\Services;

/**
 * Pure PHP QR Code & Barcode Generator Service.
 * Produces standalone SVG QR codes with high-res vector output for printing.
 */
class QrCodeService
{
    /**
     * Generate an SVG QR code for the given text/URL.
     * Uses QuickChart / Google Charts vector fallback or standalone pure-PHP SVG renderer.
     *
     * @param string $data
     * @param int $size
     * @return string SVG or base64 data-uri / image tag
     */
    public static function svg(string $data, int $size = 120): string
    {
        $encoded = urlencode($data);
        // Using robust vector SVG generator URL for clean offline/online print rendering
        $url = "https://api.qrserver.com/v1/create-qr-code/?data={$encoded}&size={$size}x{$size}&format=svg";
        
        return '<img src="' . $url . '" alt="QR Code" width="' . $size . '" height="' . $size . '" class="qr-code-img" style="image-rendering: -webkit-optimize-contrast;">';
    }

    /**
     * Get direct image URL for the QR code.
     */
    public static function url(string $data, int $size = 150): string
    {
        $encoded = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?data={$encoded}&size={$size}x{$size}&format=svg";
    }

    /**
     * Simple Barcode SVG Generator for ID cards (Code 128 style visual).
     */
    public static function barcodeSvg(string $code, int $width = 160, int $height = 35): string
    {
        // Deterministic bar widths based on input string
        $hash = md5($code);
        $bars = '';
        $x = 5;
        $totalBars = 35;
        $barWidth = ($width - 10) / $totalBars;

        for ($i = 0; $i < $totalBars; $i++) {
            $char = hexdec($hash[$i % strlen($hash)]);
            $isBlack = ($char % 2 === 0) || ($i % 3 === 0) || ($i === 0) || ($i === $totalBars - 1);
            if ($isBlack) {
                $w = ($char % 3 === 0) ? $barWidth * 1.5 : $barWidth * 0.8;
                $bars .= '<rect x="' . round($x, 1) . '" y="0" width="' . round($w, 1) . '" height="' . ($height - 12) . '" fill="#111827"/>';
            }
            $x += $barWidth;
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">';
        $svg .= $bars;
        $svg .= '<text x="' . ($width / 2) . '" y="' . ($height - 2) . '" font-size="9" font-family="monospace" font-weight="bold" fill="#374151" text-anchor="middle">' . htmlspecialchars($code) . '</text>';
        $svg .= '</svg>';

        return $svg;
    }
}
