<?php
namespace NaiveUserState;

use DateTime;

class Cookie
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
    public static function createCookie(string $name,
        string $value = '',
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $http_only = false,
        string $same_site = ''): string
    {
        $cookie = ["{$name}={$value}"];
        $cookie[] = "Expires=" . DateTime::createFromFormat("U", $expires, timezone_open('UTC'))->format(DateTime::COOKIE);
        $cookie[] = "Path={$path}";
        if ($domain) {
            $cookie[] = "Domain={$domain}"; // makes the cookie accessible under this domain and subdomains
        }
        if ($secure) {
            $cookie[] = "Secure"; // tells the client to send this cookie only with secure (HTTPS) requests
        }
        if ($http_only) {
            $cookie[] = "HTTPOnly"; // Disallow client-side access (from JavaScript)
        }
        if ($same_site) {
            $cookie[] = "SameSite={$same_site}"; // See https://www.owasp.org/index.php/SameSite
        }
        return implode("; ", $cookie);
    }
}
