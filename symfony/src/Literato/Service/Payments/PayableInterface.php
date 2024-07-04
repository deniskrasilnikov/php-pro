<?php

namespace Literato\Service\Payments;

interface PayableInterface
{
    # Get price in cents of the payable
    public function getPaymentPrice(): int;
    # Get subject text of the payable
    public function getPaymentSubject(): string;

}