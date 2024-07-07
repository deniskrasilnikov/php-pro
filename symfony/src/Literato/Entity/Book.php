<?php

declare(strict_types=1);

namespace Literato\Entity;

use Literato\Service\Printing\PrintableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column,
    DiscriminatorColumn,
    DiscriminatorMap,
    Entity,
    GeneratedValue,
    Id,
    InheritanceType,
    JoinColumn,
    ManyToOne,
    Table};
use Literato\Entity\Enum\BookType;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Exception\BookValidationException;
use Literato\Repository\BookRepository;
use Symfony\Component\Serializer\Attribute as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: BookRepository::class)]
#[Table(name: 'book')]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap(BookType::ENTITY_MAP)]
abstract class Book implements BookInterface, PrintableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    #[Serialize\Groups(['book_list', 'book_item'])]
    private int $id;

    #[Column(length: 150)]
    #[Assert\Length(min: 5, minMessage: 'Name must be at least {{ limit }} characters long',
    )]
    #[Serialize\Groups(['book_list'])]
    private string $name;

    #[Column(length: 13)]
    #[Assert\Isbn(type: Assert\Isbn::ISBN_10,)]
    #[Serialize\Groups(['book_list', 'book_item'])]
    private string $isbn10;

    #[Column(type: 'text')]
    protected string $text;

    #[ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    #[Serialize\Groups(['author_item'])]
    #[Serialize\MaxDepth(1)]
    private Author $author;

    /** @var Genre[] */
    #[Column(type: 'simple_array', enumType: Genre::class)]
    #[Serialize\Groups(['book_list', 'book_item'])]
    private array $genres = [];

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

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
        return array_flip(BookType::ENTITY_MAP)[get_called_class()];
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    // Polymorphism example. Method will have different implementation in child classes.
    abstract public function validateText(): bool;

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

    public function getPrintData(): string
    {
        return $this->text;
    }

    public function getGenres(): array
    {
        return $this->genres;
    }
}