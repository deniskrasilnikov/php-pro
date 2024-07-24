<?php

declare(strict_types=1);

namespace Literato\Bundle\PaymentBundle\Gateway;

use Literato\Bundle\PaymentBundle\Event\PaymentEvent;
use Literato\Bundle\PaymentBundle\PayableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PaymentGateway implements PaymentGatewayInterface
{
    private const STATUS_SUCCESS = 'success';

    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }


    public function makePayment(PayableInterface $payable, UserInterface $user): array
    {
        $event = new PaymentEvent($payable, $user);
        $this->dispatcher->dispatch($event, PaymentEvent::BEFORE_PAYMENT);

        //
        //  payment logic ...

        $result = [
            'subject' => $payable->getPaymentSubject(),
            "price" => $payable->getPaymentAmount(),
            'user' => $user->getUserIdentifier(),
            'status' => self::STATUS_SUCCESS
        ];

        $event->setResult($result);
        $this->dispatcher->dispatch($event, PaymentEvent::AFTER_PAYMENT);

        return $event->getResult();
    }
}