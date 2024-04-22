<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

use Literato\Author;
use Literato\Book;

use Literato\Genre;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

try {
    $publisher = new Publisher();
    $publisher->setName('Folio');
    $publisher->setAddress('12 Main Street, Anytown, USA 6503');

    $firstNovelette = new Novelette();
    $firstNovelette->setName('Unbelievable Adventure');
    $firstNovelette->setIsbn('978-3-16-148410-0');
    $firstNovelette->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');
    $firstNovelette->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');
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

    echo "\n";
} catch (TextWordLengthException|BookValidationException $e) {
    $log = new Logger('default');
    $log->pushHandler(new StreamHandler(__DIR__ . '/../log/errors.log', Level::Warning));
    $log->error($e);
}
