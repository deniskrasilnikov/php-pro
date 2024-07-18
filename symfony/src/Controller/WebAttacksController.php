<?php

namespace App\Controller;

use App\DTO\SendMoney;
use App\Entity\Client;
use App\Form\SendMoneyType;
use App\Form\XSSClientType;
use App\Module\Literato\Repository\BookRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class WebAttacksController extends AbstractController
{
    #[Route('/web-attacks', name: 'app_webattacks')]
    public function webAttacks(EntityManagerInterface $entityManager): Response
    {
        return $this->render('web-attacks.html.twig', [
            // форма, вразлива до XSS
            'xssVulnerableForm' => $this->createForm(
                XSSClientType::class
            ),
            // форма , вразлива до СSRF
            'csrfVulnerableForm' => $this->createForm(
                SendMoneyType::class,
                new SendMoney('TXguLRFtrAFrEDA17WuPfrxB84jVzJcNNV', 1200)
            ),
            'clients' => $entityManager->getRepository(Client::class)->findAll()
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/book-search', name: 'app_webattacks_search')]
    public function bookSearch(#[MapQueryParameter] ?string $bookName, BookRepository $bookRepository): Response
    {
        if ($bookName) {
            $books = $bookRepository->findByNamePart($bookName);
        }

        return $this->render('web-attacks/books.html.twig', [
            'books' => $books ?? [],
            'bookName' => $bookName
        ]);
    }

    #[Route('/client', name: 'app_webattacks_xss')]
    public function newClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(XSSClientType::class, $client = new Client());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', "Client created");
        } else {
            $this->addFlash('danger', "Client form is not valid.");
        }

        return $this->redirectToRoute('app_webattacks');
    }

    #[Route('/send-money', name: 'app_webattacks_sendmoney')]
    public function sendMoney(Request $request): Response
    {
        $amount = $request->get('amount');
        $address = $request->get('address');

        if (!$this->isCsrfTokenValid('send-money', $request->get('_token'))) {
            throw new BadRequestHttpException('Invalid request');
        }

        $this->addFlash('success', "$amount USD sent to $address address.");

        return $this->redirectToRoute('app_webattacks');
    }

    #[Route('/send-money-form', name: 'app_webattacks_sendmoney_form')]
    public function sendMoneyForm(Request $request): Response
    {
        $form = $this->createForm(SendMoneyType::class, $sendMoney = new SendMoney());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // send money through some  service
            $this->addFlash('success', "$sendMoney->amount USD sent to $sendMoney->address address.");
        } else {
            $this->addFlash('danger', "Send money form is not valid.");
        }

        return $this->redirectToRoute('app_webattacks');
    }
}
