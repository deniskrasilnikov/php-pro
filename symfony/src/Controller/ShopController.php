<?php

declare(strict_types=1);

namespace App\Controller;

use App\Module\Shop\Entity\Edition;
use App\Module\Shop\Entity\Order;
use App\Module\Shop\Repository\EditionRepository;
use App\Module\Shop\Service\OrderService;
use Literato\Bundle\PaymentBundle\Gateway\PaymentGatewayInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/shop')]
class ShopController extends AbstractController
{
    #[Route('/')]
    public function index(EditionRepository $editionRepository): Response
    {
        return $this->render('shop/index.html.twig', [
            'editions' => $editionRepository->findAll()
        ]);
    }

    #[Route('/editions/{id}/order', name: 'app_shop_order')]
    public function createOrder(Edition $edition, OrderService $orderService): Response
    {
        return $this->render('shop/order.html.twig', ['order' => $orderService->createOrder($edition)]);
    }

    #[Route('/orders/{orderNumber}', name: 'app_shop_order_show')]
    public function showOrder(Order $order): Response
    {
        return $this->render('shop/order.html.twig', ['order' => $order]);
    }

    #[Route('/orders/{orderNumber}/payment', name: 'app_shop_order_payment')]
    public function orderPayment(
        Order $order,
        #[CurrentUser] $user,
        PaymentGatewayInterface $paymentGateway
    ): Response {
        $result = $paymentGateway->makePayment($order, $user);

        return $this->render('shop/payment_result.html.twig', [
            'result' => $result,
            'order' => $order
        ]);
    }
}