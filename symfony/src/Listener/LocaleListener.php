<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class, priority: 20)]
readonly class LocaleListener
{
    public function __construct(private string $defaultLocale)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // перевіряємо, чи локаль присутня в _locale параметрі запиту
        if ($locale = $request->get('_locale')) {
            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        } else {
            // інакше задаємо локаль зі значення в сесії.. або ставимо локаль за замовченням
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }
}