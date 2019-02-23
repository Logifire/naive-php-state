# naive-php-state

The purpose of this library is to bridge the PHP session implementation, and the PSR 7, 15 standards.

Add the `PhpStateMiddleware` to your middleware stack, and use the `SessionService` to start a new session instead of `session_start()`.

You can optionally use the `SessionCollection` instead of `$_SESSION`.

```
...
/* @var ServerRequestInterface $server_request */
$session_service = new SessionService($server_request);

// Starts a writable session, this is the default you are used to when calling session_start()
$session_service->startWriteRead();

// Now you can use sessions 
$_SESSION['content'] = 'Hello World';

// Alternative
$session_collection = new SessionCollection();
$session_collection->setString('content', 'Hello World');
...
```
## Cookie abstraction
This library also comes with a cookie abstraction, if you need to set custom cookies.
```
...
// The PhpStateMiddleware has an implicit dependency on this service, must be the same reference
$response_cookie_service = new ResponseCookieService();
...
$cookie = new ResponseCookie('name', 'value');
$cookie->setExpires(strtotime('+ 14 days'));

$response_cookie_service->addCookie($cookie);
...
```
---
***NOTE*** 
There is no implementation of the `cache_limit` option. You should, however, be able to use most of the session options.