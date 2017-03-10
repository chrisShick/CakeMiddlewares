<?php
namespace chrisShick\CakeMiddlewares\Test\TestCase\Utilities;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use chrisShick\CakeMiddlewares\Utilities\IdentifierTrait;
use FriendsOfCake\TestUtilities\AccessibilityHelperTrait;
use Psr\Http\Message\ServerRequestInterface;

class testClass {
    use IdentifierTrait;

    protected $_defaultConfig = [];
}

class UtilitesTestCase extends TestCase
{
    use AccessibilityHelperTrait;

    /**
     * Test object
     *
     * @var object
     */
    private $testObject;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->testObject = new testClass();
        $this->setReflectionClassInstance($this->testObject);
    }

    /**
     * Test setIdentifierConfig
     */
    public function testSetIdentifierConfig()
    {
        $config = [
            'test' => 'value'
        ];

        $expected = $config + [
            'identifier' => function (ServerRequestInterface $request) {
                return $request->clientIp();
            }
        ];

        $actual = $this->callProtectedMethod('_setIdentifierConfig', [$config], $this->testObject);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test setIdentifier
     */
    public function testSetIdentifier()
    {
        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.211.12'
            ]
        ]);

        $expected = '192.168.211.12';
        $this->callProtectedMethod('_setIdentifier', [$request], $this->testObject);
        $actual = $this->getProtectedProperty('identifier', $this->testObject);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test setIdentifierFailed
     */
    public function testSetIdentifierFailed()
    {
        $this->testObject = new testClass();
        $this->setReflectionClassInstance($this->testObject);

        $request = new ServerRequest([
            'environment' => [
                'REMOTE_ADDR' => '192.168.211.12'
            ]
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->callProtectedMethod('_setIdentifier', [$request], $this->testObject);
    }
}
