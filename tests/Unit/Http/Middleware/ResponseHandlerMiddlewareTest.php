<?php

namespace Liopoos\Booze\Tests\Unit\Http\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Liopoos\Booze\Exception\AccessDeniedHttpException;
use Liopoos\Booze\Exception\ApiException;
use Liopoos\Booze\Exception\NotFoundHttpException;
use Liopoos\Booze\Exception\UnauthorizedHttpException;
use Liopoos\Booze\Http\Middleware\ResponseHandlerMiddleware;
use Liopoos\HttpCode\Http;
use PHPUnit\Framework\TestCase;

class ResponseHandlerMiddlewareTest extends TestCase
{
    private $middleware;

    protected function setUp(): void
    {
        $this->middleware = new ResponseHandlerMiddleware();
    }

    public function testIsSuccessfulWithSuccessfulStatusCode()
    {
        $response = new Response(200);
        
        $this->assertTrue($this->middleware->isSuccessful($response));
    }

    public function testIsSuccessfulWithCreatedStatusCode()
    {
        $response = new Response(201);
        
        $this->assertTrue($this->middleware->isSuccessful($response));
    }

    public function testIsSuccessfulWithClientErrorStatusCode()
    {
        $response = new Response(400);
        
        $this->assertFalse($this->middleware->isSuccessful($response));
    }

    public function testIsSuccessfulWithServerErrorStatusCode()
    {
        $response = new Response(500);
        
        $this->assertFalse($this->middleware->isSuccessful($response));
    }

    public function testInvokeWithSuccessfulResponse()
    {
        $jsonData = ['message' => 'success'];
        $jsonString = json_encode($jsonData);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $jsonString);
        rewind($resource);
        
        $stream = new Stream($resource);
        $response = new Response(200, ['Content-Type' => 'application/json'], $stream);
        
        $result = ($this->middleware)($response);
        
        $this->assertNotNull($result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testHandleErrorResponseWithUnauthorized()
    {
        $this->expectException(UnauthorizedHttpException::class);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Unauthorized');
        rewind($resource);
        
        $stream = new Stream($resource);
        $response = new Response(Http::HTTP_UNAUTHORIZED, ['Content-Type' => 'text/plain'], $stream);
        
        ($this->middleware)($response);
    }

    public function testHandleErrorResponseWithNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Not Found');
        rewind($resource);
        
        $stream = new Stream($resource);
        $response = new Response(Http::HTTP_NOT_FOUND, ['Content-Type' => 'text/plain'], $stream);
        
        ($this->middleware)($response);
    }

    public function testHandleErrorResponseWithForbidden()
    {
        $this->expectException(AccessDeniedHttpException::class);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Forbidden');
        rewind($resource);
        
        $stream = new Stream($resource);
        $response = new Response(Http::HTTP_FORBIDDEN, ['Content-Type' => 'text/plain'], $stream);
        
        ($this->middleware)($response);
    }

    public function testHandleErrorResponseWithGenericError()
    {
        $this->expectException(ApiException::class);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, 'Internal Server Error');
        rewind($resource);
        
        $stream = new Stream($resource);
        $response = new Response(500, ['Content-Type' => 'text/plain'], $stream);
        
        ($this->middleware)($response);
    }
}
