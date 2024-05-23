<?php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener]
class ApiRequestListener
{
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            if (!$request->headers->has('content-type') || $request->headers->get('content-type') !== 'application/json') {
                $response = new JsonResponse([
                    'error' => 'Acesso negado!'
                ]);

                $event->setResponse($response, Response::HTTP_BAD_REQUEST);
            }
        }
    }
}