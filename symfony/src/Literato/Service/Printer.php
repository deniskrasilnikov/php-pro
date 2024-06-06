<?php

declare(strict_types=1);

namespace Literato\Service;

use LogicException;

class Printer implements PrinterInterface
{
    public function print(PrintableInterface $printable, ?string $format = 'html'): void
    {
        if ($format == 'simple') {
            echo $printable->getPrintData();
        } elseif ($format == 'html' || $format === null) {
            $printData = str_replace("\n", '<br>', $printable->getPrintData());
            echo "<html lang=\"en\"><body style='font: 1.2rem Fira Sans, sans-serif;'>$printData</body></html>";
        } else {
            throw new LogicException("Unknown printing format: $format");
        }
    }
}