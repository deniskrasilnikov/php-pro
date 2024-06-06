<?php

namespace Literato\Service;

interface PrinterInterface
{
    public function print(PrintableInterface $printable, ?string $format): void;
}