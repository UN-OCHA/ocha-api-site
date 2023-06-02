<?php 

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PutListener
{
    private $decoratedListener;

    public function __construct($decoratedListener)
    {
        $this->decoratedListener = $decoratedListener;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        try {
            $this->decoratedListener->onKernelRequest($event);
        } catch (NotFoundHttpException $exception) {
            $request = $event->getRequest();

            if (Request::METHOD_PUT !== $request->getMethod()) {
                throw $exception;
            }

            // Build an empty entity.
            $resourceClass = $request->attributes->get('_api_resource_class');

            /** @var \App\Entity\KeyFigures $key_figure */
            $key_figure = new $resourceClass();
            $key_figure->setId($request->attributes->get('id'));

            $request->attributes->set('data', $key_figure);
        }
    }
}