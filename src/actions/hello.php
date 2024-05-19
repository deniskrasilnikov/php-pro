<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

# сторінка за замовчанням
return function (): Response {
    return new Response('Hello from Web PHP project!');
};