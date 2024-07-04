<?php

declare(strict_types=1);

namespace Literato\Service\Printing;

use LogicException;
use Twig\Environment;

# ФАБРИКА
readonly class PrinterFactory
{
    public function __construct(private Environment $twig)
    {
    }

    public function createPrinter(?string $format = null): PrinterInterface
    {
        if ($format == 'simple' || $format === null) {
            return new SimplePrinter();
        } elseif ($format == 'advanced') {
            return new AdvancedPrinter(new SimplePrinter()); // DECORATOR
        } elseif ($format == 'html') {
            return new HtmlPrinter($this->twig);
        } else {
            throw new LogicException("Unknown printing format: $format");
        }
    }
}