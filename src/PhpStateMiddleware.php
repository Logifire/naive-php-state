<?php
namespace NaivePhpState;

use NaivePhpState\Utility\ResponseCookieHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PhpStateMiddleware implements MiddlewareInterface
{

    /**
     * @var SessionService
     */
    private $session_service;

    /**
     * @var ResponseCookieHandler
     */
    private $response_cookie_handler;

    public function __construct(ResponseCookieHandler $response_cookie_handler, SessionService $session_service)
    {
        $this->response_cookie_handler = $response_cookie_handler;
        $this->session_service = $session_service;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $client_sesion_id = $this->session_service->getClientSessionId();

        $response = $this->response_cookie_handler->handleClientSessionId($response, $client_sesion_id);

        $response = $this->response_cookie_handler->addCustomCookies($response);

        if (session_status() == PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return $response;
    }
}
