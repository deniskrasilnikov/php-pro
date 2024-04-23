<?php

declare(strict_types=1);

namespace Literato;

use Literato\Exceptions\BookValidationException;

abstract class Book
{
    use PublisherAware;

    private string $name;
    private string $isbn;
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
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
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
             strtoupper($this->getName()) . " ({$this->getType()})",
            'Author' => $this->getAuthor()->getFullName(),
            'ISBN' => $this->getIsbn(),
            'Genres' => implode(', ', array_column($this->genres, 'value')),
            'Publisher' => $this->publisher->getName() . " ({$this->publisher->getAddress()})",
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
        array_walk($genres, fn($genre) => $genre instanceof Genre || throw new BookValidationException("Genre $genre is unknown"));
        $this->genres = $genres;
    }
}