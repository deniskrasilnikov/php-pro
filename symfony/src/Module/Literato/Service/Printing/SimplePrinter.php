<?php

declare(strict_types=1);

namespace App\Module\Literato\Service\Printing;

readonly class SimplePrinter implements PrinterInterface
{
    public function print(PrintableInterface $printable): string
    {
        return $printable->getPrintData();
    }
}