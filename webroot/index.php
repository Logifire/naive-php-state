<?php

use NanoMiddleware\RequestHandler;
use NanoPhpState\PhpStateMiddleware;
use NanoPhpState\ResponseCookie;
use NanoPhpState\ResponseCookieService;
use NanoPhpState\SessionService;
use NanoPhpState\Utility\ResponseCookieHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$psr_17_factory = new Psr17Factory();

$request_creator = new ServerRequestCreator(
    $psr_17_factory,
    $psr_17_factory,
    $psr_17_factory,
    $psr_17_factory);

$request = $request_creator->fromGlobals();

$cookie_response_service = new ResponseCookieService();

$session_service = new SessionService($request);

$inner_middleware = new Class($cookie_response_service, $session_service) implements MiddlewareInterface {

    /**
     * @var SessionService
     */
    private $session_service;

    /**
     * @var ResponseCookieService
     */
    private $service;

    public function __construct(ResponseCookieService $service, SessionService $session_service)
    {
        $this->service = $service;
        $this->session_service = $session_service;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session_service->startWriteRead();
        $cookie = new ResponseCookie('TestName', 'Test value');
        $this->service->add($cookie);
        return new Response();
    }
};


$handler = new RequestHandler($psr_17_factory);

$response_cookie_handler = new ResponseCookieHandler($cookie_response_service);

$handler->addMiddleware(new PhpStateMiddleware($response_cookie_handler));

$handler->addMiddleware($inner_middleware);

$response = $handler->handle($request);

$emitter = new SapiEmitter();

$emitter->emit($response);

echo 'Hello World';
