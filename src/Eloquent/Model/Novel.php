<?php

declare(strict_types=1);

namespace Eloquent\Model;

use Literato\Entity\Exception\BookValidationException;

/**
 * @property string|null $synopsis
 */
class Novel extends Book
{
    public const WORD_LENGTH = 10;

    /**
     * {@inheritDoc}
     */
    public function getFullInfo(): array
    {
        $fullInfo = parent::getFullInfo();
        $fullInfo['Synopsis'] = $this->synopsis;

        return $fullInfo;
    }

    /**
     * @throws BookValidationException
     */
    protected function validateText(string $text): void
    {
        parent::validateText($text);

        // word length validation
        if (str_word_count($text) < static::WORD_LENGTH) {
            throw new BookValidationException(
                sprintf('The Novel text must have at least %d words', static::WORD_LENGTH)
            );
        }
    }
}