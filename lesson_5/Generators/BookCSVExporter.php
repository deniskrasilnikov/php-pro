<?php

namespace Generators;

use Literato\Book;

final class BookCSVExporter
{
    public function exportBooks(iterable $books, string $exportFilename): void
    {
        $csv = '';

        foreach ($books as $book) {
            /**  @var Book $book */
            $csv .= implode(',', $book->getFullInfo()) . "\n";
        }

        file_put_contents($exportFilename, $csv);
    }
}