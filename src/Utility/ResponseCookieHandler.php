<?php
namespace NanoPhpState\Utility;

use NanoPhpState\ResponseCookie;
use NanoPhpState\ResponseCookieService;
use Psr\Http\Message\ResponseInterface;

class ResponseCookieHandler
{

    /**
     * @var ResponseCookieService
     */
    private $cookie_response_service;

    public function __construct(ResponseCookieService $cookie_response_service)
    {
        $this->cookie_response_service = $cookie_response_service;
    }

    public function addCustomCookies(ResponseInterface $response): ResponseInterface
    {
        /* @var $response_coookie ResponseCookie */
        foreach ($this->cookie_response_service->list() as $response_coookie) {
            $cookie_value = ResponseCookieHeaderCreator::getValue($response_coookie);

            // Multiple Set-Cookie: https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies#The_Set-Cookie_and_Cookie_headers
            $response = $response->withAddedHeader(ResponseCookieHeaderCreator::HEADER_NAME, $cookie_value);
        }

        return $response;
    }

    /**
     * Session cookie generated based on PHP configuration.
     * @see http://php.net/manual/en/session.configuration.php
     */
    public function handleClientSessionId(ResponseInterface $response, ?string $client_session_id): ResponseInterface
    {
        // If session_destroy() has been called, clear the cookie
        if (session_status() === PHP_SESSION_NONE && $client_session_id !== null) {
            $response = $this->clearClientSessionId($response);
        }

        // If new session is started
        // Client may have sent an invalid session id
        if (session_status() == PHP_SESSION_ACTIVE && $client_session_id !== session_id()) {
            $response = $this->addClientSessionId($response);
        }

        return $response;
    }

    public function addClientSessionId(ResponseInterface $response): ResponseInterface
    {
        $cookie_params = session_get_cookie_params();

        $cookie_params_liftime = (int) $cookie_params['lifetime'];
        $expires = $cookie_params_liftime ? time() + $cookie_params_liftime : 0;

        $same_site = $cookie_params['samesite'] ?? ''; // PHP 7.3.0

        $response_cookie = new ResponseCookie(session_name(), session_id());
        $response_cookie->setExpires($expires);
        $response_cookie->setPath($cookie_params['path']);
        $response_cookie->setDomain($cookie_params['domain']);
        $response_cookie->setSecure($cookie_params['secure']);
        $response_cookie->setHttpOnly($cookie_params['httponly']);
        $response_cookie->setSameSite($same_site);

        $cookie_value = ResponseCookieHeaderCreator::getValue($response_cookie);

        $response = $response->withAddedHeader(ResponseCookieHeaderCreator::HEADER_NAME, $cookie_value);

        return $response;
    }

    public function clearClientSessionId(ResponseInterface $response): ResponseInterface
    {
        $response_cookie = new ResponseCookie(session_name(), '');
        $response_cookie->setExpires(12345678); // Expires the cookie

        $cookie_value = ResponseCookieHeaderCreator::getValue($response_cookie);

        $response = $response->withAddedHeader(ResponseCookieHeaderCreator::HEADER_NAME, $cookie_value);

        return $response;
    }
}
