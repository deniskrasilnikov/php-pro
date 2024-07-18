<?php

namespace App\Controller;

use App\Module\Literato\Entity\Edition;
use App\Module\Literato\Manager\EditionManager;
use App\Module\Literato\Repository\EditionRepository;
use App\Module\Literato\Service\Payments\PaymentGatewayInterface;
use App\Module\Literato\Service\Printing\PrinterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class EditionController extends AbstractController
{
    #[Route('/best-sellers/{count}', name: 'app_edition_best_sellers')]
    public function bestSellers(EditionRepository $editionRepository, Request $request, int $count = 3): Response
    {
        return new JsonResponse($editionRepository->findBestSellers($count, $request->query->get('publisher')));
    }

    /** Print edition with given id */
    #[Route('/edition/{id}/printable', name: 'app_edition_print')]
    public function print(PrinterFactory $printerFactory, Edition $edition, Request $request): Response
    {
        $printer = $printerFactory->createPrinter($request->get('format'));

        return new Response($printer->print($edition));
    }

    #[Route('/edition/{id}/published', name: 'app_edition_published')]
    public function publish(Edition $edition, EditionManager $editionManager): Response {

        $editionManager->publish($edition);

        return $this->redirectToRoute('app_crud_edition_index');
    }

    #[Route('/edition/{id}/payment', name: 'app_edition_payment')]
    public function payment(
        Edition $edition,
        #[CurrentUser] $user,
        PaymentGatewayInterface $paymentGateway
    ): JsonResponse {

        $status = $paymentGateway->makePayment($edition, $user);

        return new JsonResponse($status);
    }
}
