<?php

namespace Liopoos\Booze\Tests\Unit\Exception;

use GuzzleHttp\Exception\TransferException;
use Liopoos\Booze\Exception\AccessDeniedHttpException;
use Liopoos\Booze\Exception\ApiException;
use Liopoos\Booze\Exception\NotFoundHttpException;
use Liopoos\Booze\Exception\UnauthorizedHttpException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testApiExceptionExtendsTransferException()
    {
        $exception = new ApiException('Test error');
        
        $this->assertInstanceOf(TransferException::class, $exception);
        $this->assertEquals('Test error', $exception->getMessage());
    }

    public function testNotFoundHttpExceptionExtendsApiException()
    {
        $exception = new NotFoundHttpException('Not found');
        
        $this->assertInstanceOf(ApiException::class, $exception);
        $this->assertInstanceOf(TransferException::class, $exception);
        $this->assertEquals('Not found', $exception->getMessage());
    }

    public function testUnauthorizedHttpExceptionExtendsApiException()
    {
        $exception = new UnauthorizedHttpException('Unauthorized');
        
        $this->assertInstanceOf(ApiException::class, $exception);
        $this->assertInstanceOf(TransferException::class, $exception);
        $this->assertEquals('Unauthorized', $exception->getMessage());
    }

    public function testAccessDeniedHttpExceptionExtendsApiException()
    {
        $exception = new AccessDeniedHttpException('Access denied');
        
        $this->assertInstanceOf(ApiException::class, $exception);
        $this->assertInstanceOf(TransferException::class, $exception);
        $this->assertEquals('Access denied', $exception->getMessage());
    }

    public function testApiExceptionWithCode()
    {
        $exception = new ApiException('Error', 500);
        
        $this->assertEquals('Error', $exception->getMessage());
        $this->assertEquals(500, $exception->getCode());
    }

    public function testNotFoundHttpExceptionWithCode()
    {
        $exception = new NotFoundHttpException('Resource not found', 404);
        
        $this->assertEquals('Resource not found', $exception->getMessage());
        $this->assertEquals(404, $exception->getCode());
    }

    public function testUnauthorizedHttpExceptionWithCode()
    {
        $exception = new UnauthorizedHttpException('Invalid credentials', 401);
        
        $this->assertEquals('Invalid credentials', $exception->getMessage());
        $this->assertEquals(401, $exception->getCode());
    }

    public function testAccessDeniedHttpExceptionWithCode()
    {
        $exception = new AccessDeniedHttpException('Forbidden resource', 403);
        
        $this->assertEquals('Forbidden resource', $exception->getMessage());
        $this->assertEquals(403, $exception->getCode());
    }
}
