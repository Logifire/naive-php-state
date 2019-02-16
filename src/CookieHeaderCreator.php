<?php
namespace NaiveUserState;

use DateTime;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie
 */
class CookieHeaderCreator
{

    public const HEADER_NAME = 'Set-Cookie';

    /**
     * @param string $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $http_only
     * @param string $same_site
     * 
     * @return string
     */
    public static function getHeaderValue(string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $http_only = false,
        string $same_site = ''): string
    {
        $cookie = ["{$name}={$value}"];

        if ($expires !== 0) {
            // The value 0 means "until the browser is closed"
            // If not specified, the cookie will have the lifetime of a session cookie
            $cookie[] = "Expires=" . DateTime::createFromFormat("U", $expires, timezone_open('UTC'))->format(DateTime::COOKIE);
        }

        $cookie[] = "Path={$path}";

        if ($domain) {
            // Makes the cookie accessible under this domain and subdomains
            $cookie[] = "Domain={$domain}";
        }

        if ($secure) {
            // Tells the client to send this cookie only with secure (HTTPS) requests
            $cookie[] = "Secure";
        }

        if ($http_only) {
            // Disallow client-side access (from JavaScript)
            $cookie[] = "HTTPOnly";
        }

        if ($same_site) {
            // See https://www.owasp.org/index.php/SameSite
            $cookie[] = "SameSite={$same_site}";
        }

        return implode("; ", $cookie);
    }
}
