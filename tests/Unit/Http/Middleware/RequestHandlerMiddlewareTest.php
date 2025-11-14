<?php

namespace Liopoos\Booze\Tests\Unit\Http\Middleware;

use GuzzleHttp\Psr7\Request;
use Liopoos\Booze\Http\Middleware\RequestHandlerMiddleware;
use PHPUnit\Framework\TestCase;

class RequestHandlerMiddlewareTest extends TestCase
{
    private $middleware;

    protected function setUp(): void
    {
        $this->middleware = new RequestHandlerMiddleware();
    }

    public function testSetHeaders()
    {
        $headers = [
            'Authorization' => 'Bearer token123',
            'Content-Type' => 'application/json'
        ];
        
        $result = $this->middleware->setHeaders($headers);
        
        $this->assertInstanceOf(RequestHandlerMiddleware::class, $result);
    }

    public function testInvokeWithSingleHeader()
    {
        $headers = ['Authorization' => 'Bearer token123'];
        $this->middleware->setHeaders($headers);
        
        $request = new Request('GET', 'https://example.com');
        $result = ($this->middleware)($request);
        
        $this->assertInstanceOf(Request::class, $result);
        $this->assertTrue($result->hasHeader('Authorization'));
        $this->assertEquals('Bearer token123', $result->getHeaderLine('Authorization'));
    }

    public function testInvokeWithMultipleHeaders()
    {
        $headers = [
            'Authorization' => 'Bearer token123',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
        $this->middleware->setHeaders($headers);
        
        $request = new Request('POST', 'https://example.com');
        $result = ($this->middleware)($request);
        
        $this->assertInstanceOf(Request::class, $result);
        $this->assertTrue($result->hasHeader('Authorization'));
        $this->assertTrue($result->hasHeader('Content-Type'));
        $this->assertTrue($result->hasHeader('Accept'));
        $this->assertEquals('Bearer token123', $result->getHeaderLine('Authorization'));
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
        $this->assertEquals('application/json', $result->getHeaderLine('Accept'));
    }

    public function testInvokeWithNoHeaders()
    {
        $request = new Request('GET', 'https://example.com');
        $result = ($this->middleware)($request);
        
        $this->assertInstanceOf(Request::class, $result);
        $this->assertEquals($request->getUri(), $result->getUri());
        $this->assertEquals($request->getMethod(), $result->getMethod());
    }

    public function testInvokePreservesExistingHeaders()
    {
        $headers = ['X-Custom-Header' => 'custom-value'];
        $this->middleware->setHeaders($headers);
        
        $request = new Request('GET', 'https://example.com', ['User-Agent' => 'TestAgent']);
        $result = ($this->middleware)($request);
        
        $this->assertTrue($result->hasHeader('User-Agent'));
        $this->assertTrue($result->hasHeader('X-Custom-Header'));
        $this->assertEquals('TestAgent', $result->getHeaderLine('User-Agent'));
        $this->assertEquals('custom-value', $result->getHeaderLine('X-Custom-Header'));
    }
}
