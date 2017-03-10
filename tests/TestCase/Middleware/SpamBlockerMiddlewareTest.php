<?php
namespace chrisShick\CakeMiddlewares\Test\TestCase\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use chrisShick\CakeMiddlewares\Middleware\SpamBlockerMiddleware;
use SebastianBergmann\CodeCoverage\RuntimeException;

class SpamBlockerMiddlewareTest extends TestCase
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
        $expected = $this->_getExpectedConfig();

        $middleware = new SpamBlockerMiddleware();
        $result = $middleware->getConfig();

        $this->assertEquals($expected, $result);

        $this->expectException(\RuntimeException::class);
        
        $middleware = new SpamBlockerMiddleware([
            'spammerFile' => 'does-not-exist.txt'
        ]);

    }

    /**
     * Test __construct with edited config
     */
    public function testEditedArrayConstructor()
    {
        $expected = $this->_getExpectedConfig('My message to everyone', ['my.spammers.com']);

        $middleware = new SpamBlockerMiddleware([
            'message' => 'My message to everyone',
            'spammers' => ['my.spammers.com']
        ]);
        $result = $middleware->getConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test __construct with edited config
     */
    public function testEditedFileConstructor()
    {
        $spamFile = dirname(__DIR__) . '/spamfile.txt';
        $expected = $this->_getExpectedConfig('My message to everyone', ['my.spammers.com'], $spamFile);

        $middleware = new SpamBlockerMiddleware([
            'message' => 'My message to everyone',
            'spammers' => ['my.spammers.com'],
            'spammersFile' => $spamFile
        ]);
        $result = $middleware->getConfig();

        $this->assertEquals($expected, $result);
    }

    public function testInvoke()
    {
        $middleware = new SpamBlockerMiddleware([
            'spammers' => ['my.spammers.com']
        ]);

        $response = new Response();
        $request = new ServerRequest([]);
        $request = $request->withHeader('Referer', 'https://github.com');

        $result = $middleware(
            $request,
            $response,
            function ($request, $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(200, $result->getStatusCode());

        $response = new Response();
        $request = new ServerRequest([]);
        $request = $request->withHeader('Referer', 'https://my.spammers.com');

        $result = $middleware(
            $request,
            $response,
            function ($request, $response) {
                return $response;
            }
        );

        $this->assertInstanceOf('Cake\Http\Response', $result);
        $this->assertEquals(403, $result->getStatusCode());
        $this->assertEquals('We do not allow spam. Get out of here!', (string)$result->getBody());
    }

    /**
     * Get the expected config to assert
     *
     * @param string $message Message to set for expected message
     * @param array $spammers Array of spammers to block
     * @return array Array of expected config
     */
    protected function _getExpectedConfig($message = '', array $spammers = [], $spammerFile = '')
    {
        if (empty($message)) {
            $message = 'We do not allow spam. Get out of here!';
        }
        if (empty($spammerFile)) {
            $spammerFile = dirname(dirname(CAKE_CORE_INCLUDE_PATH)) . '/piwik/referrer-spam-blacklist/spammers.txt';
        }

        $spammersList = file($spammerFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $spammers = array_merge($spammers, $spammersList);

        return [
            'message' => $message,
            'spammers' => $spammers,
            'spammersFile' => $spammerFile
        ];
    }
}
