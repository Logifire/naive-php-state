<?php
namespace NaiveUserState;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{

    public const SESSION_KEY = 'naiveuserstate.session';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $options = [
            'cache_limiter' => '', // Disable cache headers http://php.net/manual/en/function.session-cache-limiter.php
            'use_cookies' => 0, // Prevent PHP writing session cookie
            'use_only_cookies' => 1, // Only fetch session id from cookie
        ];

        $cookie = $request->getCookieParams();
        if (isset($cookie[session_name()])) {
            session_id($cookie[session_name()]);
        }

        session_start($options);

//        $session_collection = new SessionCollection($_SESSION);
//
//        $request = $request->withAttribute(self::SESSION_KEY, $session_collection);
        $response = $handler->handle($request);

        return $response;
    }
}
