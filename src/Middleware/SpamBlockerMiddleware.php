<?php
namespace chrisShick\CakeMiddlewares\Middleware;

use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;

class SpamBlockerMiddleware
{
    use InstanceConfigTrait;

    /**
     * Default Configuration for the SpamBlocker Middleware
     *
     * @var array
     */
    protected $_defaultConfig = [
        'message' => "We do not allow spam. Get out of here!",
        'spammers' => [],
        'spammersFile' => '/piwik/referrer-spam-blacklist/spammers.txt'
    ];

    /**
     * SpamBlockerMiddleware constructor.
     *
     * @param array $config customized configuration options for SpamBlocker Middleware
     */
    public function __construct(array $config = [])
    {
        $this->_defaultConfig['spammersFile'] = dirname(dirname(CAKE_CORE_INCLUDE_PATH)).$this->_defaultConfig['spammersFile'];

        $this->setConfig($config);

        $spammerFile = $this->getConfig('spammersFile');
        if (!is_file($spammerFile)) {
            throw new \RuntimeException(sprintf('The spammers file "%s" doest not exists', $spammerFile));
        }

        $spammersList = file($spammerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->setConfig('spammers', $spammersList);
    }

    /**
     *
     *
     * @param /Psr/Http/Message/ServerRequestInterface $request $request ServerRequest object
     * @param /Psr/Http/Message/ResponseInterface $response Response object
     * @param callable $next next middleware call
     * @return /Psr/Http/Message/ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $referrer = parse_url($request->getHeaderLine('Referer'), PHP_URL_HOST);
        $referrer = preg_replace('/^(www\.)/i', '', $referrer);

        if (in_array($referrer, $this->getConfig('spammers'), true)) {
            $stream = new Stream('php://memory', 'wb+');
            $stream->write((string)$this->getConfig('message'));

            return $response->withStatus(403)
                ->withBody($stream);
        }

        return $next($request, $response);
    }
}
