<?php

use NaiveMiddleware\RequestHandler;
use NaivePhpState\PhpStateMiddleware;
use NaivePhpState\ResponseCookieService;
use NaivePhpState\Tests\Utility\TestMiddleware;
use NaivePhpState\Utility\ResponseCookieHandler;
use NaivePhpState\Utility\ResponseCookieHeaderCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class MiddlewareTest extends TestCase
{

    private $handler;

    /**
     * Called before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $psr_17_factory = new Psr17Factory();

        $cookie_response_service = new ResponseCookieService();
        $response_cookie_handler = new ResponseCookieHandler($cookie_response_service);

        $handler = new RequestHandler($psr_17_factory);
        $handler->addMiddleware(new PhpStateMiddleware($response_cookie_handler));

        $handler->addMiddleware(new TestMiddleware($cookie_response_service));

        $this->handler = $handler;
    }

    public function testResponseCookieService()
    {
        $expected_cookie_string = "TestName=Test value; Expires=Saturday, 01-Jan-2000 00:00:00 UTC; Path=/test-path; Domain=example.org; Secure; HTTPOnly; SameSite=lax";
        $server_request = new ServerRequest('GET', '/');

        $response = $this->handler->handle($server_request);
        
        $header_string = $response->getHeaderLine(ResponseCookieHeaderCreator::HEADER_NAME);
        $this->assertSame($expected_cookie_string, $header_string);
    }
}
