<?php

require_once 'Novelette.php';
require_once 'Novel.php';
require_once 'Author.php';

try {
    $firstNovelette = new Novelette();
    $firstNovelette->setName('Unbelievable Adventure');
    $firstNovelette->setIsbn('978-3-16-148410-0');
    $firstNovelette->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.');


    $latestNovel = new Novel(); // todo change to Novelette at start
    $latestNovel->setName('Beyond the Edge');
    $latestNovel->setIsbn('768-6-02-940352-0');
    $latestNovel->setText(file_get_contents(__DIR__ . '/beyond_the_edge.txt'));
    $latestNovel->setSynopsis('This is Beyond the Edge plot short description...'); // used only for Novel

    $author = new Author();
    $author->setFirstName('John');
    $author->setLastName('Doe');
    $author->addBook($firstNovelette); // AGGREGATION
    $author->addBook($latestNovel); // AGGREGATION

    foreach ($author->getBooks() as $book) {
        echo "\n" . implode("\n", $book->getFullInfo());
        echo "\n\n{$book->getShortText()}\n";
    }

} catch (Exception $e) {
    echo $e;
}