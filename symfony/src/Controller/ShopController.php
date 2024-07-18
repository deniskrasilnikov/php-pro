<?php

declare(strict_types=1);

namespace App\Controller;

use App\Module\Shop\Entity\Edition;
use App\Module\Shop\Entity\Order;
use App\Module\Shop\Repository\EditionRepository;
use App\Module\Shop\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}