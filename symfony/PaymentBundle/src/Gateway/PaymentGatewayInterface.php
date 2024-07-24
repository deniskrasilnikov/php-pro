<?php

namespace Literato\Bundle\PaymentBundle\Gateway;

use Literato\Bundle\PaymentBundle\PayableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface PaymentGatewayInterface
{
    /** Make payment and return payment result info. */
    public function makePayment(PayableInterface $payable, UserInterface $user): array;
}