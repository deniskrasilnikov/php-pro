<?php

declare(strict_types=1);

namespace App\Module\Literato\Service;

use Exception;

/** Сервіс-клас що генерує рандомну картинку заданого розміру, в форматі PNG */
final class RandomImage
{
    private const ARC_STYLES = [
        IMG_ARC_PIE,
        IMG_ARC_CHORD,
        IMG_ARC_EDGED,
        IMG_ARC_NOFILL
    ];

    /**
     * @throws Exception
     */
    public function png(int $width, int $height): string
    {
        $im = imagecreatetruecolor($width, $height);

        for ($i = 0; $i < 10; $i++) {
            switch (random_int(1, 2)) {
                case 1:
                    $this->randomArc($im, $width, $height);
                    break;
                case 2:
                    $this->randomPolygon($im, $width, $height);
                    break;
            }
        }


        ob_start();
        imagepng($im, null, 5);
        imagedestroy($im);

        return ob_get_clean();
    }

    /**
     * Creates a random arc of a random color
     * @param resource $im The image resource
     * @param int $x
     * @param int $y
     * @return void
     * @throws Exception
     */
    private function randomArc($im, int $x, int $y): void
    {
        $cx = random_int(0, $x);
        $cy = random_int(0, $y);
        $w = random_int(0, $x);
        $h = random_int(0, $y);
        $s = random_int(0, 360);
        $e = random_int(0, 360);
        $col = imagecolorallocatealpha($im, ...$this->randomColorAlpha());
        $style = self::ARC_STYLES[random_int(0, 3)];
        imagefilledarc($im, $cx, $cy, $w, $h, $s, $e, $col, $style);
    }

    /**
     * Generates an array of random alpha color values.
     * @return array [r, g, b, a]
     * @throws Exception
     */
    private function randomColorAlpha(): array
    {
        return [
            random_int(0, 255),
            random_int(0, 255),
            random_int(0, 255),
            random_int(0, 127)
        ];
    }

    /**
     * Create a random polygon with number of points between 0 & $max_pts
     * @param resource $im The image resource
     * @param int $x
     * @param int $y
     * @return void
     * @throws Exception
     */
    private function randomPolygon($im, int $x, int $y): void
    {
        $color = imagecolorallocatealpha($im, ...$this->randomColorAlpha());
        $pts = $this->randomPts(random_int(3, 20), $x, $y);
        $num = count($pts) / 2;
        imagefilledpolygon($im, $pts, $num, $color);
    }

    /**
     * Generates a set of random points for a polygon [x1,y1, x2,y2,...]
     * @param int $length Number of sets of points to generate
     * @return array The resulting array of points
     * @throws Exception
     */
    private function randomPts(int $length, int $x, int $y): array
    {
        $pts = [];
        for ($n = 0; $n < $length; $n++) {
            $pts[] = random_int(0, $x);
            $pts[] = random_int(0, $y);
        }
        return $pts;
    }
}