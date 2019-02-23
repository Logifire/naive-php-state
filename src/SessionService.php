<?php
namespace NaivePhpState;

use NaivePhpState\Utility\ClientSessionIdTrait;
use Psr\Http\Message\ServerRequestInterface;

class SessionService
{

    use ClientSessionIdTrait;

    /**
     * @var array
     */
    private $options;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    public function __construct(ServerRequestInterface $request, array $custom_options = [])
    {

        $this->request = $request;

        // Session security: http://php.net/manual/en/session.security.ini.php
        $options = [
            // Disable cache headers http://php.net/manual/en/function.session-cache-limiter.php
            'cache_limiter' => '',
            // Prevent PHP writing the session cookie
            'use_cookies' => 0,
            // Only fetch session id from cookie
            'use_only_cookies' => 1,
            // Session ID may leak from bookmarked URL if on
            'use_trans_sid' => 0,
            // If uninitialized session ID is sent from browser, new session ID is sent to browser. 
            // Applications are protected from session fixation via session adoption with strict mode.
            'use_strict_mode' => 1
        ];

        $this->options = $options + $custom_options;
    }

    public function startWriteRead(): void
    {
        $this->start();
    }

    /**
     * Read and close rightaway to avoid locking the session file 
     * and blocking other pages
     */
    public function startRead(): void
    {
        $options = [
            'read_and_close' => 1,
        ];

        $this->start($options);
    }

    /**
     * Starts PHP session support, and disables the default autogenerated headers.
     * The directive session.auto_start should be turned off (default).
     */
    private function start(array $custom_options = []): void
    {
        $options = $this->options + $custom_options;

        $client_session_id = $this->getClientSessionId($this->request);
        if ($client_session_id !== null) {
            session_id($client_session_id);
        }

        session_start($options);
    }
}
