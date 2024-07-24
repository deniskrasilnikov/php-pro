<?php

namespace Literato\Bundle\PaymentBundle;

interface PayableInterface
{
    # Get amount to pay in cents
    public function getPaymentAmount(): int;

    # Get subject text of the payable
    public function getPaymentSubject(): string;
}