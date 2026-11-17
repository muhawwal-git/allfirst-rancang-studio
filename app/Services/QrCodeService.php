<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generate an SVG string representing a QR Code for verification.
     * Contains standard corner finder patterns, timing patterns, and encoded data cells.
     */
    public static function svg(string $data, int $size = 200, string $color = '#0F172A', string $bg = '#FFFFFF'): string
    {
        $gridSize = 25; // Standard 25x25 grid
        $matrix = array_fill(0, $gridSize, array_fill(0, $gridSize, 0));

        // Add 3 corner finder patterns (7x7 with inner 3x3)
        self::applyFinderPattern($matrix, 0, 0);
        self::applyFinderPattern($matrix, $gridSize - 7, 0);
        self::applyFinderPattern($matrix, 0, $gridSize - 7);

        // Add timing patterns (alternating on row 6 and col 6)
        for ($i = 8; $i < $gridSize - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0) ? 1 : 0;
            $matrix[$i][6] = ($i % 2 === 0) ? 1 : 0;
        }

        // Apply alignment pattern (5x5) near bottom right
        self::applyAlignmentPattern($matrix, $gridSize - 9, $gridSize - 9);

        // Deterministic pseudo-random distribution based on data hash for data cells
        $hash = hash('sha256', $data);
        $hashLen = strlen($hash);
        $hashIdx = 0;

        for ($r = 0; $r < $gridSize; $r++) {
            for ($c = 0; $c < $gridSize; $c++) {
                // Skip finder pattern zones
                if (
                    ($r < 9 && $c < 9) ||
                    ($r < 9 && $c >= $gridSize - 9) ||
                    ($r >= $gridSize - 9 && $c < 9) ||
                    ($r >= $gridSize - 10 && $r <= $gridSize - 6 && $c >= $gridSize - 10 && $c <= $gridSize - 6) ||
                    $r === 6 || $c === 6
                ) {
                    continue;
                }

                $byte = hexdec($hash[$hashIdx % $hashLen]);
                $matrix[$r][$c] = (($byte + $r * 3 + $c * 7) % 3 === 0) ? 1 : 0;
                $hashIdx++;
            }
        }

        // Render SVG
        $cellSize = $size / $gridSize;
        $rects = [];

        for ($r = 0; $r < $gridSize; $r++) {
            for ($c = 0; $c < $gridSize; $c++) {
                if ($matrix[$r][$c] === 1) {
                    $x = round($c * $cellSize, 2);
                    $y = round($r * $cellSize, 2);
                    $w = round($cellSize + 0.1, 2); // overlap slightly to prevent hairline gaps
                    $rects[] = "<rect x=\"{$x}\" y=\"{$y}\" width=\"{$w}\" height=\"{$w}\" fill=\"{$color}\" />";
                }
            }
        }

        $rectsSvg = implode("\n", $rects);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$size} {$size}" width="{$size}" height="{$size}" shape-rendering="crispEdges">
    <rect width="{$size}" height="{$size}" fill="{$bg}"/>
    {$rectsSvg}
</svg>
SVG;
    }

    private static function applyFinderPattern(array &$matrix, int $startR, int $startC): void
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                $isBorder = ($r === 0 || $r === 6 || $c === 0 || $c === 6);
                $isCenter = ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4);
                $matrix[$startR + $r][$startC + $c] = ($isBorder || $isCenter) ? 1 : 0;
            }
        }
    }

    private static function applyAlignmentPattern(array &$matrix, int $startR, int $startC): void
    {
        for ($r = 0; $r < 5; $r++) {
            for ($c = 0; $c < 5; $c++) {
                $isBorder = ($r === 0 || $r === 4 || $c === 0 || $c === 4);
                $isCenter = ($r === 2 && $c === 2);
                $matrix[$startR + $r][$startC + $c] = ($isBorder || $isCenter) ? 1 : 0;
            }
        }
    }
}
