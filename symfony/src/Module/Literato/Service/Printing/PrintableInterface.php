<?php

namespace App\Module\Literato\Service\Printing;

interface PrintableInterface
{
    public function getPrintData(): string;
}