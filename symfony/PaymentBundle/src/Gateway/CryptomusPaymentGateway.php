<?php

declare(strict_types=1);

namespace Literato\Bundle\PaymentBundle\Gateway;

use Cryptomus\Api\Client as Cryptomus;
use Cryptomus\Api\RequestBuilderException;
use Literato\Bundle\PaymentBundle\PayableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

# АДАПТЕР: адатпує використання бібліотеки з несумісним інтерфейсом під нашу систему і НАШ інтерфейс
readonly class CryptomusPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(private string $payoutKey, private string $merchantUuid)
    {

    }

    public function makePayment(PayableInterface $payable, UserInterface $user): array
    {
        $payout = Cryptomus::payout($this->payoutKey, $this->merchantUuid);

        $data = [
            'amount' => $payable->getPaymentAmount(),
            'currency' => 'USD',
            'network' => 'TRON',
            'order_id' => '555321',
            'address' => 'TXguLRFtrAFrEDA17WuPfrxB84jVzJcNNV',
            'is_subtract' => '1',
            'url_callback' => 'https://example.com/callback'
        ];

        try {
            $result = $payout->create($data);
        } catch (\Exception $e) {
            $result = [
                'subject' => $payable->getPaymentSubject(),
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        return (array)$result;
    }
}