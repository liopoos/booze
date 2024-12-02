<?php

namespace Liopoos\Booze\Http\Middleware;

use GuzzleHttp\Psr7\Request;

class RequestHandlerMiddleware
{
    protected $headers = [];

    /**
     * Set client headers
     *
     * @param array $headers
     * @return RequestHandlerMiddleware
     */
    public function setHeaders(array $headers): RequestHandlerMiddleware
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Handles all response
     *
     * @param Request $request
     * @param array $options
     * @return Request
     */
    public function __invoke(Request $request, array $options = []): Request
    {
        foreach ($this->headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $request;
    }
}
