<?php

namespace App\Module\Literato\Service\Printing;

interface PrinterInterface
{
    public function print(PrintableInterface $printable): string;
}