<?php

namespace Liopoos\Booze;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Liopoos\Booze\Http\HttpClient;
use Liopoos\Booze\Http\Middleware\RequestHandlerMiddleware;
use Liopoos\Booze\Http\Middleware\ResponseHandlerMiddleware;

abstract class Client extends HttpClient
{
    protected $guzzleHandler;

    public function __construct($guzzleOptions = [])
    {
        if (!isset($guzzleOptions['handler'])) {
            $guzzleOptions['handler'] = HandlerStack::create();
        }
        parent::__construct($guzzleOptions);

        $this->guzzleHandler = $this->getHandler();
        $this->guzzleHandler->push(Middleware::mapResponse(new ResponseHandlerMiddleware()));
    }

    /**
     * Gets the HandlerStack for Guzzle
     *
     * @return HandlerStack
     */
    protected final function getHandler(): HandlerStack
    {
        $config = $this->httpClient->getConfig();

        if (is_null($config['handler'])) {
            $config['handler'] = HandlerStack::create();
        }

        return $config['handler'];
    }

    /**
     * Set Client headers
     *
     * @param array $headers
     * @return Client
     */
    protected final function withHeaders(array $headers): Client
    {
        $this->guzzleHandler->push(Middleware::mapRequest((new RequestHandlerMiddleware())->setHeaders($headers)));

        return $this;
    }
}
