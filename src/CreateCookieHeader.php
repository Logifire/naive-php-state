<?php
namespace NaiveUserState;

use DateTime;

class CreateCookieHeader
{

    public const SET_COOKIE = 'Set-Cookie';

    /**
     * 
     * @param string $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $http_only
     * @param string $same_site
     * @return string
     */
    public static function getHeadline(string $name,
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
