<?php
namespace chrisShick\CakeMiddlewares\Middleware;

use chrisShick\CakeMiddlewares\Utilities\IdentifierTrait;
use Geocoder\ProviderAggregator;
use Geocoder\Provider\FreeGeoIp;
use Geocoder\Geocoder;
use Ivory\HttpAdapter\FopenHttpAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GeolocationMiddleware
{
    use IdentifierTrait;

    /**
     * Default Configuration for the Gelocation Middleware
     *
     * @var array
     */
    protected $_defaultConfig = [
        'attribute' => 'geolocation',
        'geocoder' => null
    ];

    /**
     * @var Geocoder
     */
    private $geocoder;

    /**
     * GeolocationMiddleware constructor.
     *
     * @param array $config customized configuration options for Geolocation Middleware
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($this->_setIdentifierConfig($config));

        $this->_setGeocoder($this->getConfig('geocoder'));
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
        $this->_setIdentifier($request);

        if ($this->_identifier !== null && is_string($this->getConfig('attribute'))) {
            $location = $this->geocoder->geocode($this->_identifier);

            $request->withAttribute($this->getConfig('attribute'), $location);
        }

        return $next($request, $response);
    }

    /**
     * Sets the geocoder class property.
     *
     * @param Geocoder|null $geocoder Geocoder object
     * @return void
     */
    protected function _setGeocoder(Geocoder $geocoder = null)
    {
        if ($geocoder === null) {
            $geocoder = new ProviderAggregator();
            $geocoder->registerProvider(new FreeGeoIp(new FopenHttpAdapter()));
        }
        $this->geocoder = $geocoder;
    }
}
