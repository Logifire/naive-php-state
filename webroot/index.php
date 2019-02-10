<?php

use NaiveUserState\SessionService;

//ini_set('session.use_cookies', 0);
//ini_set('session.use_only_cookies', 1);
// Disable cache headers: 
//session_cache_limiter('');

$options = [
    'cache_limiter' => '', // Disable cache headers http://php.net/manual/en/function.session-cache-limiter.php
    'use_cookies' => 0, // Prevent PHP writing session cookie
    'use_only_cookies' => 1, // Only fetch session id from cookie
];

if (isset($_COOKIE[session_name()])) {
    session_id($_COOKIE[session_name()]);
}

session_start($options);

require_once dirname(__DIR__) . '/vendor/autoload.php';

var_dump(session_name(), session_id(), $_SESSION);

$cookie_parms = session_get_cookie_params();

var_dump($cookie_parms);

$_SESSION['test'] = true;
//$session = new SessionService($_SESSION);
//
//if (!$session->hasString('welcome')) {
//    $session->setString('welcome', 'Hello World');
//}

var_dump($_SESSION);

//echo $session->getString('welcome');

//$session->apply($_SESSION);
