<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequireServiceNameListener
{
    private $decoratedListener;

    public function __construct($decoratedListener)
    {
        $this->decoratedListener = $decoratedListener;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if ($request->headers->has('accept') && $request->headers->get('accept') == 'application/json' && !$request->headers->has('APP-NAME')) {
            throw new BadRequestException('appname header is mandatory.');
        }

        $this->decoratedListener->onKernelRequest($event);
    }
}