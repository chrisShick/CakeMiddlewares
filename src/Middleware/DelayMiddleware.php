<?php
namespace chrisShick\CakeMiddlewares\Middleware;

use Cake\Core\InstanceConfigTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Stream;

class DelayMiddleware
{
    use InstanceConfigTrait;

    /**
     * Default Configuration for the Firewall Middleware
     *
     * @var array
     */
    protected $_defaultConfig = [
        'header' => 'X-Delay-Time',
        'attribute' => 'delayTime',
        'seconds' => [1,2]
    ];

    /**
     * SpamBlockerMiddleware constructor.
     *
     * @param array $config customized configuration options for SpamBlocker Middleware
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }


    /**
     *
     *
     * @param ServerRequestInterface $request $request ServerRequest object
     * @param /Psr/Http/Message/ResponseInterface $response Response object
     * @param callable $next next middleware call
     * @return /Psr/Http/Message/ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $ms = $this->getConfig('seconds');

        if (is_array($ms)) {
            $ms = rand(round($ms[0] * 1000000), round($ms[1] * 1000000));
        } else {
            $ms = round($ms * 1000000);
        }

        usleep($ms);

        $attribute = $this->getConfig('attribute');
        $header = $this->getConfig('header');

        if (is_string($attribute)) {
            $request = $request->withAttribute($this->getConfig('attribute'), round($ms / 1000000));
        }

        $response = $next($request, $response);

        if (is_string($header)) {
            $response = $response->withHeader($this->getConfig('header'));
        }

        return $response;
    }
}
