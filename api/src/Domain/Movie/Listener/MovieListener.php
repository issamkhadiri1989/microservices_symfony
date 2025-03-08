<?php

declare(strict_types=1);

namespace App\Domain\Movie\Listener;

use ApiPlatform\Elasticsearch\Paginator;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\ApiResource\Movie;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::VIEW, priority: EventPriorities::PRE_WRITE, method: "attach")]
final class MovieListener
{
    private string $baseUrl;

    public function __construct(#[Autowire(env: "STORAGE_PUBLIC_URL")] string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function attach(ViewEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_GET) {
            return;
        }

        /** @var Paginator $controller */
        $controller = $event->getControllerResult();

        /** @var Movie $item */
        foreach ($controller->getIterator() as $item) {
            if (null !== $item->coverPath) {
                $item->coverPath = \sprintf('%s/%s', $this->baseUrl, $item->coverPath);
            }
        }
    }
}
