<?php

namespace App\Module\Literato\Entity;

interface BookInterface
{
    public function getFullInfo(): array;
    public function getAuthor(): Author;
    public function getIsbn10(): string;
    public function getName(): string;
    public function getId(): int;
    public function getText(): string;
}