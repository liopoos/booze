<?php

namespace Liopoos\Booze\Tests\Unit\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Liopoos\Booze\Http\HttpClient;
use Liopoos\Booze\Http\Middleware\ResponseHandlerMiddleware;
use PHPUnit\Framework\TestCase;

class TestHttpClient extends HttpClient
{
    // Concrete implementation for testing abstract class
}

class HttpClientTest extends TestCase
{
    private $mockHandler;
    private $handlerStack;
    private $httpClient;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $this->handlerStack = HandlerStack::create($this->mockHandler);
        // Add the ResponseHandlerMiddleware to wrap responses properly
        $this->handlerStack->push(Middleware::mapResponse(new ResponseHandlerMiddleware()));
    }

    private function createHttpClient()
    {
        return new TestHttpClient(['handler' => $this->handlerStack]);
    }

    public function testGetHttpClient()
    {
        $httpClient = $this->createHttpClient();
        
        $result = $httpClient->getHttpClient();
        
        $this->assertInstanceOf(GuzzleClient::class, $result);
    }

    public function testGetRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Success')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->get('https://httpbin.org/get');
        
        $this->assertEquals('Success', $result);
    }

    public function testGetRequestWithQuery()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Query result')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->get('https://httpbin.org/get', ['key' => 'value']);
        
        $this->assertEquals('Query result', $result);
    }

    public function testGetRequestWithHeaders()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Header result')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->get('https://httpbin.org/get', [], ['Authorization' => 'Bearer token']);
        
        $this->assertEquals('Header result', $result);
    }

    public function testPostRequest()
    {
        $this->mockHandler->append(
            new Response(201, ['Content-Type' => 'text/plain'], 'Created')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->post('https://httpbin.org/post', ['name' => 'test']);
        
        $this->assertEquals('Created', $result);
    }

    public function testPostJsonRequest()
    {
        $this->mockHandler->append(
            new Response(201, ['Content-Type' => 'application/json'], '{"status":"success"}')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->postJson('https://httpbin.org/post', ['name' => 'test']);
        
        $this->assertIsArray($result);
        $this->assertEquals(['status' => 'success'], $result);
    }

    public function testPostMultiPartRequest()
    {
        $this->mockHandler->append(
            new Response(201, ['Content-Type' => 'text/plain'], 'Uploaded')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->postMultiPart('https://httpbin.org/post', [
            ['name' => 'field1', 'contents' => 'value1']
        ]);
        
        $this->assertEquals('Uploaded', $result);
    }

    public function testDeleteRequest()
    {
        $this->mockHandler->append(
            new Response(204, ['Content-Type' => 'text/plain'], '')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->delete('https://httpbin.org/delete');
        
        $this->assertEquals('', $result);
    }

    public function testDeleteJsonRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'application/json'], '{"deleted":true}')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->deleteJson('https://httpbin.org/delete', ['id' => 1]);
        
        $this->assertIsArray($result);
        $this->assertEquals(['deleted' => true], $result);
    }

    public function testPatchRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'application/json'], '{"patched":true}')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->patch('https://httpbin.org/patch', ['field' => 'updated']);
        
        $this->assertIsArray($result);
        $this->assertEquals(['patched' => true], $result);
    }

    public function testPutRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Updated')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->put('https://httpbin.org/put', ['name' => 'updated']);
        
        $this->assertEquals('Updated', $result);
    }

    public function testPutJsonRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'application/json'], '{"updated":true}')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->putJson('https://httpbin.org/put', ['name' => 'updated']);
        
        $this->assertIsArray($result);
        $this->assertEquals(['updated' => true], $result);
    }

    public function testPutMultiPartRequest()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Updated')
        );
        
        $httpClient = $this->createHttpClient();
        $result = $httpClient->putMultiPart('https://httpbin.org/put', [
            ['name' => 'field1', 'contents' => 'value1']
        ]);
        
        $this->assertEquals('Updated', $result);
    }

    public function testGetHttpResponse()
    {
        $this->mockHandler->append(
            new Response(200, ['Content-Type' => 'text/plain'], 'Response')
        );
        
        $httpClient = $this->createHttpClient();
        $httpClient->get('https://httpbin.org/get');
        
        $response = $httpClient->getHttpResponse();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
