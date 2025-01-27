<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity;

use App\Module\Literato\Entity\Enum\BookType;
use App\Module\Literato\Entity\Enum\Genre;
use App\Module\Literato\Entity\Exception\BookValidationException;
use App\Module\Literato\Repository\BookRepository;
use App\Module\Literato\Service\Printing\PrintableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    OneToMany,
    Table
};
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

    /** @var Collection<int, BookTranslation> */
    #[OneToMany(targetEntity: BookTranslation::class, mappedBy: 'book', cascade: ['persist', 'remove'])]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function getTranslation(string $locale): ?BookTranslation
    {
        return $this->translations->findFirst(fn($_, $t) => $t->getLocale() == $locale);
    }

    public function addTranslation(BookTranslation $t)
    {
        if (!$this->getTranslation($t->getLocale())) {
            $this->translations[] = $t;
            $t->setBook($this);
        }
    }

    public function createTranslations(array $locales): void
    {
        foreach ($locales as $locale) {
            $t = new BookTranslation($locale);
            $t->setLocale($locale);
            $this->addTranslation($t);
        }
    }

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

    public function getName(string $locale = null): string
    {
        if ($locale && ($translation = $this->getTranslation($locale))) {
            return $translation->getName();
        }

        if ($this->translations->first()) {
            return $this->translations->first()->getName();
        }

        return 'Unknown name';
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