<?php
namespace NaivePhpState\Utility;

use DateTime;
use NaivePhpState\ResponseCookie;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie
 */
class ResponseCookieHeaderCreator
{

    /**
     * Name of the response header for cookies
     */
    public const HEADER_NAME = 'Set-Cookie';

    /**
     * @param ResponseCookie $response_cookie
     * 
     * @return string
     */
    public static function getValue(ResponseCookie $response_cookie): string
    {
        $cookie = ["{$response_cookie->getName()}={$response_cookie->getValue()}"];

        if ($response_cookie->getExpires() !== 0) {
            // The value 0 means "until the browser is closed"
            // If not specified, the cookie will have the lifetime of a session cookie
            $cookie[] = "Expires=" . DateTime::createFromFormat("U", $response_cookie->getExpires(), timezone_open('UTC'))->format(DateTime::COOKIE);
        }

        $cookie[] = "Path={$response_cookie->getPath()}";

        if ($response_cookie->getDomain()) {
            // Makes the cookie accessible under this domain and subdomains
            $cookie[] = "Domain={$response_cookie->getDomain()}";
        }

        if ($response_cookie->isSecure()) {
            // Tells the client to send this cookie only with secure (HTTPS) requests
            $cookie[] = "Secure";
        }

        if ($response_cookie->isHttpOnly()) {
            // Disallow client-side access (from JavaScript)
            $cookie[] = "HTTPOnly";
        }

        if ($response_cookie->getSameSite()) {
            // See https://www.owasp.org/index.php/SameSite
            $cookie[] = "SameSite={$response_cookie->getSameSite()}";
        }

        return implode("; ", $cookie);
    }
}
