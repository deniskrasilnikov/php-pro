<?php

declare(strict_types=1);

namespace Literato\Service\Printing;

# ДЕКОРАТОР: розширює існуючу реалізацію власною логікою під тим же самим інтерфейсом.

readonly class AdvancedPrinter implements PrinterInterface
{
    public function __construct(private PrinterInterface $printer)
    {
    }

    public function print(PrintableInterface $printable): string
    {
        return "DECORATED PRINTABLE [\n" . $this->printer->print($printable) . "\n]";
    }
}