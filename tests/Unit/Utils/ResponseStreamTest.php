<?php

namespace Liopoos\Booze\Tests\Unit\Utils;

use GuzzleHttp\Psr7\Stream;
use Liopoos\Booze\Utils\ResponseStream;
use PHPUnit\Framework\TestCase;

class ResponseStreamTest extends TestCase
{
    public function testGetStreamContentsWithJsonContentType()
    {
        $jsonData = ['message' => 'success', 'code' => 200];
        $jsonString = json_encode($jsonData);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $jsonString);
        rewind($resource);
        
        $stream = new Stream($resource);
        $responseStream = new ResponseStream($stream, 'application/json');
        
        $result = $responseStream->getStreamContents();
        
        $this->assertEquals($jsonData, $result);
    }

    public function testGetStreamContentsWithJavascriptContentType()
    {
        $jsonData = ['data' => 'test', 'status' => 'ok'];
        $jsonString = json_encode($jsonData);
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $jsonString);
        rewind($resource);
        
        $stream = new Stream($resource);
        $responseStream = new ResponseStream($stream, 'application/javascript');
        
        $result = $responseStream->getStreamContents();
        
        $this->assertEquals($jsonData, $result);
    }

    public function testGetStreamContentsWithXmlContentType()
    {
        $xmlString = '<?xml version="1.0"?><root><message>success</message><code>200</code></root>';
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $xmlString);
        rewind($resource);
        
        $stream = new Stream($resource);
        $responseStream = new ResponseStream($stream, 'application/xml');
        
        $result = $responseStream->getStreamContents();
        
        $this->assertIsArray($result);
        $this->assertEquals('success', $result['message']);
        $this->assertEquals('200', $result['code']);
    }

    public function testGetStreamContentsWithPlainTextContentType()
    {
        $textData = 'plain text response';
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $textData);
        rewind($resource);
        
        $stream = new Stream($resource);
        $responseStream = new ResponseStream($stream, 'text/plain');
        
        $result = $responseStream->getStreamContents();
        
        $this->assertEquals($textData, $result);
    }

    public function testGetStreamContentsWithInvalidJson()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error decode response content to json');
        
        $invalidJson = '{invalid json}';
        
        $resource = fopen('php://memory', 'r+');
        fwrite($resource, $invalidJson);
        rewind($resource);
        
        $stream = new Stream($resource);
        $responseStream = new ResponseStream($stream, 'application/json');
        
        $responseStream->getStreamContents();
    }
}
