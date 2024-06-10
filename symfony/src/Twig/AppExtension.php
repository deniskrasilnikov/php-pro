<?php

declare(strict_types=1);

namespace App\Twig;

use Biblys\Isbn\Isbn;
use Exception;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('cents', [$this, 'formatCents']),
            new TwigFilter('isbn10', [$this, 'isbn10']),
        ];
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        return '$' . $price;
    }

    public function formatCents($number): string
    {
        return $this->formatPrice($number / 100, 2);
    }

    public function isbn10(string $isbn): string
    {
        try {
            return Isbn::convertToIsbn10($isbn);
        } catch (Exception $e) {
            $this->logger->error("Can't convert ISBN $isbn: " . $e->getMessage());
            return $isbn ;
        }
    }
}