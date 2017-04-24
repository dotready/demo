<?php

namespace DemoBundle\EventListener;

use DemoBundle\Exception\AuthenticationException;
use DemoBundle\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig_Environment;

class ExceptionListener
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * ExceptionListener constructor.
     * @param Router $router
     * @param Twig_Environment $twig
     */
    public function __construct(Router $router, Twig_Environment $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $message   = $exception->getMessage();

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message . '<br>' . $exception->getTraceAsString());

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $errorCode = $exception->getStatusCode();
            $body = $this->twig->render('errors/error'.$errorCode.'.html.twig');
            $response->setContent($body);
        } elseif ($exception instanceof AuthenticationException) {
            // user could not be authenticated
            $url = $this->router->generate('accountLoginGet');
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        } elseif ($exception instanceof ValidationException) {
            // in case of exceptions thrown from domain and
            // exception is about invalid parameters
            if ($exception->getMessage() === ValidationException::INVALID_PARAMETERS) {
                echo $exception;
                exit;
            }
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}
