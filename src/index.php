<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

# створюємо карту маршрутів проєкту
$routes = new RouteCollection();

$routes->add('hello', new Route('/'));
# маршрут співпаде з будь яким HTTP методом в запиті
$routes->add('book', new Route('/book/{name}', ['name' => 'New Book']));
# маршрут співпаде тільки у випадку HTTP методу GET в запиті
$routes->add('edition', new Route('/edition/{isbn10}', methods: 'GET'));

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    # шукаємо співпадіння шляху веб запиту з одним із наших маршрутів, екстрактуємо дані маршруту у разі успіху
    extract($attributes = $matcher->match($request->getPathInfo()), EXTR_SKIP);
    # викликаємо конкретну дію (функцію) з окремого файлу скрипта по назві маршруту
    $handler = require sprintf(__DIR__ . '/actions/%s.php', $_route);
    $response = call_user_func($handler, $request, $attributes);

    if (!$response instanceof Response) {
        return new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (MethodNotAllowedException $exception) {
    $response = new Response('HTTP method not allowed', 405);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();
