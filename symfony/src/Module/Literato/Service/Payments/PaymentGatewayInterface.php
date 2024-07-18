<?php

namespace App\Module\Literato\Service\Payments;

use Symfony\Component\Security\Core\User\UserInterface;

interface PaymentGatewayInterface
{
    /** Make payment and return payment status info. */
    public function makePayment(PayableInterface $payable, UserInterface $user): array;
}