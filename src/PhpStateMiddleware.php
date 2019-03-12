<?php
namespace NanoPhpState;

use NanoPhpState\Utility\ClientSessionIdTrait;
use NanoPhpState\Utility\ResponseCookieHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PhpStateMiddleware implements MiddlewareInterface
{

    use ClientSessionIdTrait;

    /**
     * @var ResponseCookieHandler
     */
    private $response_cookie_handler;

    public function __construct(ResponseCookieHandler $response_cookie_handler)
    {
        $this->response_cookie_handler = $response_cookie_handler;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $client_sesion_id = $this->getClientSessionId($request);

        $response = $this->response_cookie_handler->handleClientSessionId($response, $client_sesion_id);

        $response = $this->response_cookie_handler->addCustomCookies($response);

        if (session_status() == PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return $response;
    }
}
