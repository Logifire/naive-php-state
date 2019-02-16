<?php
namespace NaiveUserState;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserStateMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->initiatePhpSession($request);

        $response = $handler->handle($request);

        $cookie_value = $this->getCookieHeadline();

        $response = $response->withAddedHeader(CookieHeaderCreator::HEADER_NAME, $cookie_value);

        return $response;
    }

    private function initiatePhpSession(ServerRequestInterface $request): void
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
    }

    private function getCookieHeadline(): string
    {
        $cookie_params = session_get_cookie_params();

        $expires = $cookie_params['lifetime'] ? time() + $cookie_params['lifetime'] : 0;
        $path = $cookie_params['path'];
        $domain = $cookie_params['domain'];
        $secure = $cookie_params['secure'];
        $httponly = $cookie_params['httponly'];
        $same_site = $cookie_params['samesite'] ?? ''; // PHP 7.3.0

        $cookie_value = CookieHeaderCreator::getHeaderValue(
                session_name(),
                session_id(),
                $expires,
                $path,
                $domain,
                $secure,
                $httponly,
                $same_site
        );

        return $cookie_value;
    }
}
