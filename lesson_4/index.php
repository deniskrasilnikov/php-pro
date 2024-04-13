<?php

require_once 'autoloader_psr4.php';

use Literato\Author;
use Literato\Book;
use Literato\Novel;
use Literato\Novelette;
use MyProject\Novel as MyNovel;

function printBookNamespace(Book $book)
{
    echo "\n".  '[' . get_class($book) . '] ' . $book->getName();
}

try {
    $firstNovelette = new Novelette();
    $firstNovelette->setName('Unbelievable Adventure');
    $firstNovelette->setIsbn('978-3-16-148410-0');
    $firstNovelette->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');

    $latestNovel = new Novel();
    $latestNovel->setName('Beyond the Edge');
    $latestNovel->setIsbn('768-6-02-940352-0');
    $latestNovel->setText('onec quam felis, ultricies nec, olor sit amet pellentesque e');
    $latestNovel->setSynopsis('Short description of a plot');

    $myNovel = new MyNovel();
    $myNovel->setName('My newest novel');
    $myNovel->setText('onec quam felis, ultricies nec, olor sit amet pellentesque e');
    $myNovel->setSynopsis('My novel makes synopsis word count validation');

    $author = new Author();
    $author->setFirstName('Clint');
    $author->setLastName('Eastwood');
    $author->addBook($firstNovelette);
    $author->addBook($latestNovel);
    $author->addBook($myNovel);

    echo "\n{$author->getFullName()} has written {$author->getBooksCount()} book(s):\n";
    printBookNamespace($firstNovelette);
    printBookNamespace($latestNovel);
    printBookNamespace($myNovel);
    echo "\n";

} catch (\Exception $e) {
    echo $e;
}