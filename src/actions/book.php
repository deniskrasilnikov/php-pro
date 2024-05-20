<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

# дізнатись назву книги передану в шляху запиту
return function (Request $request, array $attributes): Response {
    $html = "Book name is <b>{$attributes['name']}</b>";
    $html .= "<br />HTTP Method: "  .$request->getMethod();

    return new Response($html);
};