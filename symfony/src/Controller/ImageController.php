<?php

declare(strict_types=1);

namespace App\Controller;
use Exception;
use Literato\Service\RandomImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    # екшен генерації випадкової картинки заданого розміру
    #[Route('/random-image', name: 'app_random_image')]
    public function index(
        RandomImage $image,
        #[MapQueryParameter] int $width = 200,
        #[MapQueryParameter] int $height = 200
    ): Response {
        try {
            $response = new Response($image->png($width, $height));
            $response->headers->set('Content-Type', 'image/png');
            return $response;
        } catch (Exception) {
            return new Response();
        }
    }
}