<?php

declare(strict_types=1);

namespace Literato\DTO;

use Literato\Entity\Enum\EditionStatus;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateEdition
{
    public function __construct(
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(10000)]
        public ?int $price = null,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(1000)]
        public ?int $authorBaseReward = null,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        public ?int $authorRewardPerCopy = null,

        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100000)]
        public ?int $soldCopiesCount = null,

        public ?EditionStatus $status = null
    )
    {
    }
}