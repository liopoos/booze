<?php

namespace Liopoos\Booze\Http\Middleware;


use GuzzleHttp\Psr7\Response;
use Liopoos\Booze\Exception\AccessDeniedHttpException;
use Liopoos\Booze\Exception\ApiException;
use Liopoos\Booze\Exception\NotFoundHttpException;
use Liopoos\Booze\Exception\UnauthorizedHttpException;
use Liopoos\Booze\Utils\ResponseStream;
use Liopoos\HttpCode\Http;
use Psr\Http\Message\MessageInterface;

class ResponseHandlerMiddleware
{
    /**
     * Handles all response
     *
     * @param Response $response
     * @param array $options
     * @return MessageInterface|void
     * @throws ApiException|UnauthorizedHttpException|NotFoundHttpException|AccessDeniedHttpException
     */
    public function __invoke(Response $response, array $options = [])
    {
        $contentType = $response->getHeaderLine('Content-Type');
        $stream = new ResponseStream($response->getBody(), $contentType);

        if ($this->isSuccessful($response)) {
            return $response->withBody($stream);
        }

        $this->handleErrorResponse($response, $stream);
    }

    /**
     * Check http status code
     *
     * @param $response
     * @return bool
     */
    public function isSuccessful($response): bool
    {
        return $response->getStatusCode() < 400;
    }

    /**
     * Handles unsuccessful error codes
     *
     * @param $response
     * @param $stream
     * @throws ApiException
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws AccessDeniedHttpException
     */
    public function handleErrorResponse($response, $stream)
    {
        switch ($response->getStatusCode()) {
            case Http::HTTP_UNAUTHORIZED:
                throw new UnauthorizedHttpException($stream, Http::HTTP_UNAUTHORIZED);
            case Http::HTTP_NOT_FOUND:
                throw new NotFoundHttpException($stream, Http::HTTP_NOT_FOUND);
            case Http::HTTP_FORBIDDEN:
                throw new AccessDeniedHttpException($stream, Http::HTTP_FORBIDDEN);
            default:
                throw new ApiException($stream, $response->getStatusCode());
        }
    }
}
