<?php
namespace chrisShick\CakeMiddlewares\Test\TestCase\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use chrisShick\CakeMiddlewares\Middleware\GeolocationMiddleware;
use Geocoder\ProviderAggregator;
use Geocoder\Provider\FreeGeoIp;
use Ivory\HttpAdapter\FopenHttpAdapter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GeolocationMiddlewareTest extends TestCase
{
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test __construct
     */
    public function testConstructor()
    {
        $middleware = new GeolocationMiddleware();
        $result = $middleware->getConfig();

        $expected = [
            'attribute' => 'geolocation',
            'geocoder' => null,
            'identifier' => function (ServerRequestInterface $request) {
                $request->clientIp();
            }
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test __invoke
     */
    public function testInvoke()
    {
        $middleware = new GeolocationMIddleware();

        $response = new Response();
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '16c3:a357:a6d4:b023:95dc:3b98:6d3a:cd4'
            ]
        ]);
        $geocoder = new ProviderAggregator();
        $geocoder->registerProvider(new FreeGeoIp(new FopenHttpAdapter()));

        $expectedLocation = $geocoder->geocode('16c3:a357:a6d4:b023:95dc:3b98:6d3a:cd4');
        $resultLocation = null;

        $result = $middleware(
            $request,
            $response,
            function (ServerRequestInterface $request, ResponseInterface $response) use (&$resultLocation) {
                $resultLocation = $request->getAttribute('geolocation');
                
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($expectedLocation, $resultLocation);
    }
}
