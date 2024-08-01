<?php

declare(strict_types=1);

namespace App\Twig;

use App\Module\Literato\Entity\Enum\BookType;
use Biblys\Isbn\Isbn;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Locales;
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
            new TwigFilter('locale_name', [$this, 'getLocaleName']),
        ];
    }

    public function getLocaleName(?string $locale): string
    {
        if (null === $locale) {
            return '';
        }

        return mb_convert_case(Locales::getName($locale, $locale), MB_CASE_TITLE, 'UTF-8');
    }

    public function getBookTypes(): array
    {
        return array_column(BookType::cases(), 'value');
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
    {
        return number_format($number, $decimals, $decPoint, $thousandsSep);
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