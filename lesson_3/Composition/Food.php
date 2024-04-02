<?php

readonly class Food
{
    public function __construct(private string $type)
    {
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->getType();
    }
}