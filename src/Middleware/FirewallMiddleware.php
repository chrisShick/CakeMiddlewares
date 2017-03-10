<?php
namespace chrisShick\CakeMiddlewares\Middleware;

use chrisShick\CakeMiddlewares\Utilities\IdentifierTrait;
use M6Web\Component\Firewall\Firewall;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;

class FirewallMiddleware
{
    use IdentifierTrait;

    /**
     * Default Configuration for the Firewall Middleware
     *
     * @var array
     */
    protected $_defaultConfig = [
        'message' => 'You are not authorized to perform this action.',
        'whitelist' => [],
        'blacklist' => [],
        'defaultState' => false
    ];

    /**
     * FirewallMiddleware constructor.
     *
     * @param array $config customized configuration options for Firewall Middleware
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($this->_setIdentifierConfig($config));
    }

    /**
     * Set the whitelist ips allowed through the firewall
     *
     * @param string|array $whitelist array of allowed ips
     * @return $this
     */
    public function setWhiteList($whitelist)
    {
        if (!empty($whitelist)) {
            if (!is_array($whitelist) && is_string($whitelist)) {
                $whitelist = [$whitelist];
            }
            $this->setConfig('whitelist', $whitelist);
        }

        return $this;
    }

    /**
     * Set the blacklist ips not allowed through the firewall
     *
     * @param string|array $blacklist array of blocked ips
     * @return $this
     */
    public function setBlacklist($blacklist)
    {
        if (!empty($blacklist)) {
            if (!is_array($blacklist) && is_string($blacklist)) {
                $blacklist = [$blacklist];
            }
            $this->setConfig('blacklist', $blacklist);
        }

        return $this;
    }

    /**
     *
     *
     * @param /Psr/Http/Message/ServerRequestInterface $request ServerRequest object
     * @param /Psr/Http/Message/ResponseInterface $response Response object
     * @param callable $next next middleware call
     * @return /Psr/Http/Message/ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $firewall = new Firewall();
        $whitelist = $this->getConfig('whitelist');
        $blacklist = $this->getConfig('blacklist');

        $this->_setIdentifier($request);

        if (!empty($whitelist)) {
            $firewall->addList($whitelist, 'whitelist', true);
        }

        if (!empty($blacklist)) {
            $firewall->addList($blacklist, 'blacklist', false);
        }

        $result = $firewall->setDefaultState($this->getConfig('defaultState'))
            ->setIpAddress($this->_identifier)
            ->handle();

        if (!$result) {
            $stream = new Stream('php://memory', 'wb+');
            $stream->write((string)$this->getConfig('message'));

            return $response->withStatus(403)
                ->withBody($stream);
        }

        return $next($request, $response);
    }
}
