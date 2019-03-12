<?php
namespace NanoPhpState\Utility;

use Psr\Http\Message\ServerRequestInterface;


trait ClientSessionIdTrait
{

    private function getClientSessionId(ServerRequestInterface $request): ?string
    {
        $session_id = null;
        $cookie = $request->getCookieParams();
        if (isset($cookie[session_name()])) {
            $session_id = $cookie[session_name()];
        }
        return $session_id;
    }
}
