<?php

declare(strict_types=1);

require_once 'autoloader_psr4.php';

use Generators\BookCSVExporter;
use Generators\BookDbRepository;

function memoryUsage(): string
{
    $memoryUsage = memory_get_usage(true);

    if ($memoryUsage < 1024) {
        return $memoryUsage . " b";
    } elseif ($memoryUsage < 1048576) {
        return round($memoryUsage / 1000, 3) . " Kb";
    } else {
        return round($memoryUsage / 1000000, 3) . " Mb";
    }
}

$timeStart = microtime(true);

$bookRepository = new BookDbRepository();
$bookCSVExporter = new BookCSVExporter();

if ($argv[1]) {
    echo "WITH GENERATORS :)\n";
    $books = $bookRepository->findAllBooksAsIterable();
    var_dump($books);
    $bookCSVExporter->exportBooks($books, __DIR__ . '/books_iterable.csv');
} else {
    echo "NO GENERATORS ;(\n";
    $books = $bookRepository->findAllBooks();
    $bookCSVExporter->exportBooks($books, __DIR__ . '/books.csv');
}


$timeElapsedSecs = microtime(true) - $timeStart;
echo "\nMemory: " . memoryUsage() . "\nTime: " . round($timeElapsedSecs, 3) . " sec\n";