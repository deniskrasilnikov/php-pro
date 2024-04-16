<?php

declare(strict_types=1);

require_once 'autoloader_psr4.php';

use Literato\Author;
use Literato\Book;
use Literato\Exceptions\BookValidationException;
use Literato\Exceptions\TextWordLengthException;
use Literato\Genre;
use Literato\Novel;
use Literato\Novelette;
use Literato\Publisher;

function printBook(Book $book): void
{
    $fullInfo = $book->getFullInfo();
    echo "\n" . implode(
            "\n",
            array_map(
                fn($key, $value) => is_string($key) ? "[$key]\t$value" : $value,
                array_keys($fullInfo),
                array_values($fullInfo)
            )
        ) . "\n";
}

try {
    $publisher = new Publisher();
    $publisher->setName('Folio');
    $publisher->setAddress('12 Main Street, Anytown, USA 6503');

    $firstNovelette = new Novelette();
    $firstNovelette->setName('Unbelievable Adventure');
    $firstNovelette->setIsbn('978-3-16-148410-0');
    $firstNovelette->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');
    $firstNovelette->setGenres([Genre::Romance, Genre::Thriller]);
    $firstNovelette->setPublisher($publisher);

    $latestNovel = new Novel();
    $latestNovel->setName('Beyond the Edge');
    $latestNovel->setIsbn('768-6-02-940352-0');
    $latestNovel->setText('onec quam felis, ultricies nec, olor sit amet pellentesque e');
    $latestNovel->setGenres([Genre::SciFi, Genre::MagicalRealism]);
    $latestNovel->setSynopsis('Short description of a plot');
    $latestNovel->setPublisher($publisher);

    $author = new Author();
    $author->setFirstName('Clint');
    $author->setLastName('Eastwood');
    $author->addBook($firstNovelette);
    $author->addBook($latestNovel);
    $author->setPublisher($publisher);

    echo "\n{$author->getFullName()} has written {$author->getBooksCount()} book(s):\n";
    printBook($firstNovelette);
    printBook($latestNovel);

    echo "\nBook genres:";
    // iterate over Genre enum values
    foreach (Genre::cases() as $case) {
        echo ("\n* ".$case->value);
    }

    echo "\n";
} catch (TextWordLengthException $e) {
    echo "We have caught more specific validation exception: " . $e;
} catch (BookValidationException $e) {
    echo "We have caught generic book validation exception: " . $e;
}