<?php
namespace NaivePhpState\Tests\Utility;

use NaivePhpState\ResponseCookie;
use NaivePhpState\ResponseCookieService;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestMiddleware implements MiddlewareInterface
{

    /**
     * @var ResponseCookieService
     */
    private $response_service;

    public function __construct(ResponseCookieService $response_service)
    {

        $this->response_service = $response_service;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookie = new ResponseCookie('TestName', 'Test value');
        $cookie->setDomain('example.org');
        $cookie->setExpires(strtotime('2000-01-01 00:00:00'));
        $cookie->setHttpOnly(true);
        $cookie->setPath('/test-path');
        $cookie->setSameSite('lax');
        $cookie->setSecure(true);

        $this->response_service->add($cookie);

        return new Response();
    }
}
