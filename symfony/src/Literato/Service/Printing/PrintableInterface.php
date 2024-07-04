<?php

namespace Literato\Service\Printing;

interface PrintableInterface
{
    public function getPrintData(): string;
}