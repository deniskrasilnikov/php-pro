<?php

declare(strict_types=1);

namespace Literato\Service\Printing;

use Twig\Environment;
use Twig\Error\Error as TwigError;

readonly class HtmlPrinter implements PrinterInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function print(PrintableInterface $printable): string
    {
        try {
            return $this->twig->render('printing/print.html.twig', ['data' => $printable->getPrintData()]);
        } catch (TwigError $e) {
            throw new \RuntimeException('Rendering error', 0, $e);
        }
    }
}