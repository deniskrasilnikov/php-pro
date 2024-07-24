<?php

declare(strict_types=1);

namespace App\Listener;

use Literato\Bundle\PaymentBundle\Event\PaymentEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

# СПОСТЕРІГАЧ (СЛУХАЧ) ПОДІЇ
#[AsEventListener(event: PaymentEvent::AFTER_PAYMENT)]
class AfterPaymentListener
{
    public function __invoke(PaymentEvent $event): void
    {
        $result = $event->getResult();
        $result['additional_result'] = __METHOD__;
        $event->setResult($result);
    }
}