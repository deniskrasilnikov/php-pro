<?php

declare(strict_types=1);

namespace Literato\Entity;

use Literato\Entity\Enum\Genre;
use Literato\Entity\Exception\BookValidationException;

abstract class Book
{
    use Identity;

    private string $name;
    private string $isbn10;
    private string $text;
    private Author $author; // book AGGREGATES its author
    /** @var Genre[] */
    private array $genres;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new BookValidationException('Book name must not be empty');
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIsbn10(): string
    {
        return $this->isbn10;
    }

    /**
     * @param string $isbn10
     */
    public function setIsbn10(string $isbn10): void
    {
        $this->isbn10 = $isbn10;
    }

    /**
     * @return Author
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @param Author $author
     */
    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->validateText($text);
        $this->text = $text;
    }


    /**
     * Get book name, author name, ISBN and other info as array items (strings)
     * @return array
     */
    public function getFullInfo(): array
    {
        return [
            'Name' => $this->getName(),
            'Type' => $this->getType(),
            'Author' => $this->getAuthor()->getFullName(),
            'ISBN' => $this->getIsbn10(),
            'Genres' => implode(', ', array_column($this->genres, 'value')),
            'ShortText' => substr($this->getText(), 0, 50),
        ];
    }

    public function getType(): string
    {
        return trim(str_replace(__NAMESPACE__, '', get_called_class()), '\\');
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    // Polymorphism example. Method will have different implementation in child classes.
    abstract protected function validateText(string $text): void;

    /**
     * @param array $genres
     */
    public function setGenres(array $genres): void
    {
        array_walk(
            $genres,
            fn($genre) => $genre instanceof Genre || throw new BookValidationException("Genre $genre is unknown")
        );
        $this->genres = $genres;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres;
    }
}