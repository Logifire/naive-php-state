<?php

use NaiveMiddleware\RequestHandler;
use NaiveUserState\SessionMiddleware;
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

$inner_middleware = new Class() implements MiddlewareInterface {

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        var_dump($_SESSION);
        return new Response();
    }
};

$request = $request_creator->fromGlobals();

$handler = new RequestHandler($psr_17_factory);

$handler->addMiddleware(new SessionMiddleware());

$handler->addMiddleware($inner_middleware);

$response = $handler->handle($request);

$emitter = new SapiEmitter();

$emitter->emit($response);

echo 'Hello World';