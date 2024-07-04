<?php

namespace Literato\Service\Printing;

interface PrinterInterface
{
    public function print(PrintableInterface $printable): string;
}