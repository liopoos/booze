<?php

namespace Liopoos\Booze\Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Liopoos\Booze\Client;
use PHPUnit\Framework\TestCase;

class TestClient extends Client
{
    // Concrete implementation for testing abstract class
}

class ClientTest extends TestCase
{
    private $mockHandler;
    private $handlerStack;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $this->handlerStack = HandlerStack::create($this->mockHandler);
    }

    public function testConstructorWithDefaultHandler()
    {
        $client = new TestClient();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testConstructorWithCustomHandler()
    {
        $client = new TestClient(['handler' => $this->handlerStack]);
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testWithHeadersReturnsClientInstance()
    {
        $client = new TestClient(['handler' => $this->handlerStack]);
        
        $result = $client->withHeaders(['Authorization' => 'Bearer token']);
        
        $this->assertInstanceOf(Client::class, $result);
    }

    public function testWithHeadersWithMultipleHeaders()
    {
        $client = new TestClient(['handler' => $this->handlerStack]);
        
        $headers = [
            'Authorization' => 'Bearer token123',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        
        $result = $client->withHeaders($headers);
        
        $this->assertInstanceOf(Client::class, $result);
    }

    public function testGetHandlerReturnsHandlerStack()
    {
        $client = new TestClient(['handler' => $this->handlerStack]);
        
        // Use reflection to access protected method
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getHandler');
        $method->setAccessible(true);
        
        $handler = $method->invoke($client);
        
        $this->assertInstanceOf(HandlerStack::class, $handler);
    }

    public function testClientWithGuzzleOptions()
    {
        $options = [
            'handler' => $this->handlerStack,
            'verify' => false,
            'timeout' => 30
        ];
        
        $client = new TestClient($options);
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testClientCanMakeRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Success')
        );
        
        $client = new TestClient(['handler' => $this->handlerStack]);
        $result = $client->get('https://httpbin.org/get');
        
        $this->assertEquals('Success', $result);
    }

    public function testClientWithHeadersCanMakeRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Success with headers')
        );
        
        $client = new TestClient(['handler' => $this->handlerStack]);
        $client->withHeaders(['Authorization' => 'Bearer token']);
        
        $result = $client->get('https://httpbin.org/get');
        
        $this->assertEquals('Success with headers', $result);
    }

    public function testClientChainedWithHeaders()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Chained success')
        );
        
        $client = new TestClient(['handler' => $this->handlerStack]);
        $result = $client->withHeaders(['X-Custom' => 'Value'])->get('https://httpbin.org/get');
        
        $this->assertEquals('Chained success', $result);
    }
}
