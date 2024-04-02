<?php

abstract class Book
{
    private string $name;
    private string $isbn;
    private string $text;
    private Author $author; // book AGGREGATES its author

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
     * @throws Exception
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
            strtoupper($this->getName()),
            $this->getAuthor()->getFullName(),
            $this->getIsbn()
        ];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getShortText(): string
    {
        return substr($this->getText(), 0, 150);
    }

    // Polymorphism example. Method will have different implementation in child classes.
    abstract protected function validateText(string $text): void;
}