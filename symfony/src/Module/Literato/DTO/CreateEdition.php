<?php

declare(strict_types=1);

namespace App\Module\Literato\DTO;

use Symfony\Component\Validator\Constraints as Assert;

# Data Transfer Object
class CreateEdition
{
    public function __construct(
        #[Assert\Positive]
        public int $bookId,

        #[Assert\Positive]
        public int $publisherId,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(10000)]
        public int $price,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(1000)]
        public int $authorBaseReward
    )
    {
    }
}