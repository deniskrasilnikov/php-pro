<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
class Novel extends Book
{
    public const WORD_LENGTH = 10;

    #[Column(length: 200)]
    private ?string $synopsis;

    #[Assert\Expression("this.validateText()", message: 'Novel text must have at least ' . self::WORD_LENGTH . ' words',)]
    protected string $text;

    /**
     * {@inheritDoc}
     */
    public function getFullInfo(): array
    {
        // Polymorphism example. We override parent method adding extra functionality.

        $fullInfo = parent::getFullInfo();
        $fullInfo['Synopsis'] = $this->synopsis;

        return $fullInfo;
    }

    public function setSynopsis(string $synopsis): void
    {
        $this->synopsis = $synopsis;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * @throws Exception
     */
    public function validateText(): bool
    {
        return str_word_count($this->text) >= static::WORD_LENGTH;
    }
}