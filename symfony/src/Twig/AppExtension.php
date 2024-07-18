<?php

declare(strict_types=1);

namespace App\Twig;

use App\Module\Literato\Entity\Enum\BookType;
use Biblys\Isbn\Isbn;
use Exception;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('book_types', [$this, 'getBookTypes'])
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('cents', [$this, 'formatCents']),
            new TwigFilter('isbn10', [$this, 'isbn10']),
        ];
    }

    public function getBookTypes(): array
    {
        return array_column(BookType::cases(), 'value');
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
            return $isbn;
        }
    }
}