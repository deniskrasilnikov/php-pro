<?php

namespace MyProject;

use Exception;
use Literato\Book;

class Novel extends Book
{
    public const WORD_LENGTH = 5;
    private string $synopsis = '';


    /**
     * {@inheritDoc}
     */
    public function getFullInfo(): array
    {
        // Polymorphism example. We override parent method adding extra functionality.

        $fullInfo = parent::getFullInfo();
        $fullInfo[] = $this->synopsis;

        return $fullInfo;
    }

    /**
     * @throws Exception
     */
    public function setSynopsis(string $synopsis): void
    {
        if (str_word_count($synopsis) < static::WORD_LENGTH) {
            throw new Exception(
                sprintf('The Novel synopsis must have at least %d words. Book: %s', static::WORD_LENGTH, $this->getName())
            );
        }

        $this->synopsis = $synopsis;
    }
    /**
     * @throws Exception
     */
    protected function validateText(string $text): void
    {
        // word length validation
        if (str_word_count($text) < static::WORD_LENGTH) {
            throw new Exception(
                sprintf('The Novel text must have at least %d words. Book: %s', static::WORD_LENGTH, $this->getName())
            );
        }
    }
}