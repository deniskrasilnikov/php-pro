<?php

declare(strict_types=1);

use Literato\Entity\Edition;
use Literato\ServiceFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

# пошук Видання по ISBN коду книги
return function (Request $request, array $attributes): Response {
    $serviceFactory = new ServiceFactory();
    $entityManager = $serviceFactory->createDoctrineEntityManager();

    $queryBuilder = $entityManager->getRepository(Edition::class)
        ->createQueryBuilder('e')
        ->join('e.book', 'b')
        ->where('b.isbn10 = :isbn10');
    $query = $queryBuilder->getQuery()
        ->setParameter('isbn10', $attributes['isbn10']);
    $editions = $query->getResult();

    /** @var Edition[] $editions */
    if (count($editions)) {
        $html = "";

        foreach ($editions[0]->getFullInfo() as $key => $value) {
            $html .= "<tr><td><b>$key</b></td><td>$value</td><tr>";
        }

        return new Response("<table>$html</table>");
    } else {
        return new Response(status: Response::HTTP_NOT_FOUND);
    }
};