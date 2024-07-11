<?php

declare(strict_types=1);

namespace App\DTO;

class SendMoney
{
    public function __construct(
        public string $address = '',
        public int $amount = 0,
    )
    {
    }
}