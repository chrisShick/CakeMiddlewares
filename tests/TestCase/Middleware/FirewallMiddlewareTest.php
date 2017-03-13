<?php
namespace chrisShick\CakeMiddlewares\Test\TestCase\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use chrisShick\CakeMiddlewares\Middleware\FirewallMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FirewallMiddlewareTest extends TestCase
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
        $middleware = new FirewallMiddleware();
        $result = $middleware->getConfig();

        $expected = [
            'message' => 'You are not authorized to perform this action.',
            'whitelist' => [],
            'blacklist' => [],
            'defaultState' => false,
            'identifier' => function (ServerRequestInterface $request) {
                $request->clientIp();
            }
        ];

        $this->assertEquals($expected, $result);

        $expected = [
            'message' => 'A custom message!',
            'whitelist' => [
                '192.168.*'
            ],
            'blacklist' => [
                '127.0.0.1'
            ],
            'defaultState' => false,
            'identifier' => function (ServerRequestInterface $request) {
                $request->clientIp();
            }
        ];

        $middleware = new FirewallMiddleware($expected);
        $result = $middleware->getConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test setWhiteList
     */
    public function testSetWhiteList()
    {
        $expected = ['192.168.*'];

        $middleware = new FirewallMiddleware();
        $middleware->setWhitelist('192.168.*');

        $result = $middleware->getConfig('whitelist');

        $this->assertEquals($expected, $result);

        $middleware = new FirewallMiddleware();
        $middleware->setWhitelist($expected);

        $result = $middleware->getConfig('whitelist');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test setBlackList
     */
    public function testSetBlacklist()
    {
        $expected = ['192.168.*'];

        $middleware = new FirewallMiddleware();
        $middleware->setBlacklist('192.168.*');

        $result = $middleware->getConfig('blacklist');

        $this->assertEquals($expected, $result);

        $middleware = new FirewallMiddleware();
        $middleware->setBlacklist($expected);

        $result = $middleware->getConfig('blacklist');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test __invoke
     */
    public function testInvoke()
    {
        $middleware = new FirewallMiddleware();

        $response = new Response();
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.211.12'
            ]
        ]);

        $result = $middleware(
            $request,
            $response,
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(403, $result->getStatusCode());

        $response = new Response();
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.214.11'
            ]
        ]);

        $middleware->setConfig('defaultState', true);

        $result = $middleware(
            $request,
            $response,
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Test __invoke with customized Firewall
     */
    public function testInvokeCustom()
    {
        $middleware = new FirewallMiddleware([
            'whitelist' => [
                '192.168.211.12'
            ],
            'blacklist' => [
                '*',
            ]
        ]);

        $response = new Response();
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.211.12'
            ]
        ]);

        $result = $middleware(
            $request,
            $response,
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());

        $response = new Response();
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.214.11'
            ]
        ]);

        $result = $middleware(
            $request,
            $response,
            function (ServerRequestInterface $request, ResponseInterface $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(403, $result->getStatusCode());
    }
}
