<?php

declare(strict_types=1);

namespace App\Module\Literato\Service\Payments;

use Cryptomus\Api\Client as Cryptomus;
use Cryptomus\Api\RequestBuilderException;
use Symfony\Component\Security\Core\User\UserInterface;

# АДАПТЕР: адатпує використання бібліотеки з несумісним інтерфейсом під нашу систему і НАШ інтерфейс
class CryptomusPaymentGateway implements PaymentGatewayInterface
{
    private const PAYOUT_KEY = 'qseRhcxu6wsxhygfhyidwrrgryrrgefhPP0F1cNedoR7FGEYGA1mTgMPX8OpRcl2c3HexNedoR7FGEYGA1mTgMPI8lzKl7Ct2I43R6S1f4EAaZQKmefhSC3gVDS3rkGX';
    private const MERCHANT_UUID = 'c26b80a8-9549-4a66-bb53-774f12809249';

    /**
     * @throws RequestBuilderException
     */
    public function makePayment(PayableInterface $payable, UserInterface $user): array
    {
        $payout = Cryptomus::payout(self::PAYOUT_KEY, self::MERCHANT_UUID);

        $data = [
            'amount' => $payable->getPaymentPrice(),
            'currency' => 'USD',
            'network' => 'TRON',
            'order_id' => '555321',
            'address' => 'TXguLRFtrAFrEDA17WuPfrxB84jVzJcNNV',
            'is_subtract' => '1',
            'url_callback' => 'https://example.com/callback'
        ];

        $result = $payout->create($data);

        return (array)$result;
    }
}