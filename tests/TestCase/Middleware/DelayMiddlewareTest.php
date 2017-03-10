<?php
namespace chrisShick\CakeMiddlewares\Test\TestCase\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use chrisShick\CakeMiddlewares\Middleware\DelayMiddleware;

class DelayMiddlewareTest extends TestCase
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
        $middleware = new DelayMiddleware();
        $result = $middleware->getConfig();

        $this->assertEquals('X-Delay-Time', $result['header']);
        $this->assertEquals('delayTime', $result['attribute']);
        $this->assertEquals([1, 2], $result['seconds']);

        $expected = [
            'header' => 'My-Custom-Header',
            'attribute' => 'myCustomAttribute',
            'seconds' => 100
        ];
        $middleware = new DelayMiddleware($expected);
        $result = $middleware->getConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test __invoke
     */
    public function testInvoke()
    {
        $middleware = new DelayMiddleware([
            'seconds' => 1
        ]);

        $request = new ServerRequest();
        $response = new Response();

        $result = $middleware(
            $request,
            $response,
            function ($request, $response) {
                return $response;
            }
        );

        $expectedHeader = [
            'X-Delay-Time'
        ];

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(1, count(array_intersect($expectedHeader, array_keys($result->getHeaders()))));
        $this->assertEquals(1, (int)$result->getHeader('X-Delay-Time'));

        $middleware = new DelayMiddleware();

        $response = new Response();
        $request = new ServerRequest();

        $result = $middleware(
            $request,
            $response,
            function ($request, $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(1, count(array_intersect($expectedHeader, array_keys($result->getHeaders()))));
        $this->assertGreaterThanOrEqual(1, (int)$result->getHeader('X-Delay-Time'));
        $this->assertLessThanOrEqual(2, (int)$result->getHeader('X-Delay-Time'));
    }
}
