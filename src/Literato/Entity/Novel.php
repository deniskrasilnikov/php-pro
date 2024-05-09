<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Exception;
use Literato\Entity\Exception\TextWordLengthException;

#[Entity]
class Novel extends Book
{
    public const WORD_LENGTH = 10;

    #[Column(length: 200)]
    private ?string $synopsis;

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

    /**
     * @throws Exception
     */
    protected function validateText(string $text): void
    {
        // word length validation
        if (str_word_count($text) < static::WORD_LENGTH) {
            throw new TextWordLengthException($this, static::WORD_LENGTH);
        }
    }
}