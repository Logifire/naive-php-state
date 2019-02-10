<?php

use NaiveUserState\SessionService;

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

var_dump($_SESSION);

$session = new SessionService($_SESSION);
//$cookie = new CollectionService($_COOKIE);

if (!$session->hasString('welcome')) {
    $session->setString('welcome', 'Hello World');
}

var_dump($_SESSION);

echo $session->getString('welcome');

$session->apply($_SESSION);
