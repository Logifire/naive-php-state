<?php

use NaiveMiddleware\RequestHandler;
use NaiveUserState\Cookie;
use NaiveUserState\CookieResponseService;
use NaiveUserState\UserStateMiddleware;
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

$cookie_response_service = new CookieResponseService();

$inner_middleware = new Class($cookie_response_service) implements MiddlewareInterface {

    /**
     * @var CookieResponseService
     */
    private $service;

    public function __construct(CookieResponseService $service)
    {
        $this->service = $service;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookie = new Cookie('MyTester', 'test');
        $this->service->addCookie($cookie);
        return new Response();
    }
};

$request = $request_creator->fromGlobals();

$handler = new RequestHandler($psr_17_factory);


$handler->addMiddleware(new UserStateMiddleware($cookie_response_service));

$handler->addMiddleware($inner_middleware);

$response = $handler->handle($request);

$emitter = new SapiEmitter();

$emitter->emit($response);

echo 'Hello World';