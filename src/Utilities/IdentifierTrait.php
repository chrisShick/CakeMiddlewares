<?php
namespace chrisShick\CakeMiddlewares\Utilities;

use Cake\Core\InstanceConfigTrait;
use Psr\Http\Message\ServerRequestInterface;

trait IdentifierTrait
{
    use InstanceConfigTrait;

    /**
     * Unique client identifier
     *
     * @var string
     */
    protected $_identifier;

    /**
     * Sets the identifier class property. Uses Firewall default IP address
     * based identifier unless a callable alternative is passed.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request object
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function _setIdentifier(ServerRequestInterface $request)
    {
        $key = $this->getConfig('identifier');
        if (!is_callable($this->getConfig('identifier'))) {
            throw new \InvalidArgumentException('Firewall identifier option must be a callable');
        }
        $this->_identifier = $key($request);
    }

    /**
     * Set the default callable for the identifier
     *
     * @param array $config customized configuration options
     * @return array
     */
    protected function _setIdentifierConfig(array $config)
    {
        return $config += [
            'identifier' => function (ServerRequestInterface $request) {
                return $request->clientIp();
            }
        ];
    }
}