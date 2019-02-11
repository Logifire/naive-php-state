<?php

use NaiveContainer\ContainerFactory;
use NaiveMiddleware\RequestHandler;
use NaiveUserState\SessionCollection;
use NaiveUserState\SessionMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

//$options = [
//    'cache_limiter' => '', // Disable cache headers http://php.net/manual/en/function.session-cache-limiter.php
//    'use_cookies' => 0, // Prevent PHP writing session cookie
//    'use_only_cookies' => 1, // Only fetch session id from cookie
//];
//
//if (isset($_COOKIE[session_name()])) {
//    session_id($_COOKIE[session_name()]);
//}
//
//session_start($options);

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
        /* @var $session_collection SessionCollection */
//        $session_collection = $request->getAttribute(SessionMiddleware::SESSION_KEY);

        var_dump($_SESSION);
        return new Response();
    }
};

$request = $request_creator->fromGlobals();

$handler = new RequestHandler($psr_17_factory);

$handler->addMiddleware(new SessionMiddleware());

$handler->addMiddleware($inner_middleware);

$response = $handler->handle($request);


//var_dump(session_name(), session_id(), $_SESSION);
//
//$cookie_parms = session_get_cookie_params();
//
//var_dump($cookie_parms);
//
//$_SESSION['test'] = 'HEY';
//
//$session = new SessionCollection($_SESSION);
//
//if (!$session->hasKey('foo')) {
//    $session->setInteger('foo', 2);
//}
//var_dump($session->isString('test'), $_SESSION);
//
//var_dump($_COOKIE);