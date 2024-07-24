<?php

declare(strict_types=1);

namespace Literato\Bundle\PaymentBundle\Event;

use Literato\Bundle\PaymentBundle\PayableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PaymentEvent
{
    public const BEFORE_PAYMENT = 'before_payment';
    public const AFTER_PAYMENT = 'after_payment';
    private array $result;

    public function __construct(private readonly PayableInterface $payable, private readonly  UserInterface $user)
    {

    }

    public function setResult(array $result)
    {
        $this->result = $result;
    }

    /**
     * @return PayableInterface
     */
    public function getPayable(): PayableInterface
    {
        return $this->payable;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }
}