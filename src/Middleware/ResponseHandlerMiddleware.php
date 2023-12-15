<?php

namespace Lopoos\Booze\Middleware;


use GuzzleHttp\Psr7\Response;
use Lopoos\Booze\Exception\AccessDeniedHttpException;
use Lopoos\Booze\Exception\ApiException;
use Lopoos\Booze\Exception\NotFoundHttpException;
use Lopoos\Booze\Exception\UnauthorizedHttpException;
use Lopoos\Booze\Utils\ResponseStream;
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
            case 401:
                throw new UnauthorizedHttpException;
            case 404:
                throw new NotFoundHttpException;
            case 403:
                throw new AccessDeniedHttpException;
            default:
                throw new ApiException($stream);
        }
    }
}
