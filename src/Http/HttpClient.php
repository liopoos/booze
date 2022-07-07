<?php

namespace Lopoos\Booze\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

abstract class HttpClient
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var Response
     */
    private $httpResponse;

    /**
     * @param $guzzleOptions
     */
    public function __construct($guzzleOptions)
    {
        $this->httpClient = new Client($guzzleOptions);
    }

    /**
     * Return the http client
     *
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * Return the http response
     *
     * @return Response
     */
    public function getHttpResponse(): Response
    {
        return $this->httpResponse;
    }

    /**
     * Send a GET request
     *
     * @param string $url
     * @param array $headers
     * @param array $body
     * @return mixed
     * @throws GuzzleException
     */
    public function get(string $url, array $body = [], array $headers = [])
    {
        $this->httpResponse = $this->httpClient->get($url, [
            'headers' => $headers,
            'query' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Send a POST request
     *
     * @param string $url
     * @param array $headers
     * @param array $body
     * @return mixed
     * @throws GuzzleException
     */
    public function post(string $url, array $body = [], array $headers = [])
    {
        $this->httpResponse = $this->httpClient->post($url, [
            'headers' => $headers,
            'form_params' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a POST request with JSON data
     *
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function postJson(string $url, array $body, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->post($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a multi-part POST request
     *
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function postMultiPart(string $url, array $body, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->post($url, [
            'headers' => $headers,
            'multipart' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a DELETE request
     *
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function delete(string $url, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->delete($url, [
            'headers' => $headers,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a DELETE request with JSON
     *
     * @param string $url
     * @param array $headers
     * @param array $body
     * @return mixed
     * @throws GuzzleException
     */
    public function deleteJson(string $url, array $body = [], array $headers = [])
    {
        $this->httpResponse = $this->httpClient->delete($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a PATCH request
     *
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function patch(string $url, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->patch($url, [
            'headers' => $headers,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a PUT request
     *
     * @param string $url
     * @param array|null $body
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function put(string $url, array $body = null, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->put($url, [
            'headers' => $headers,
            'form_params' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a PUT request with JSON
     *
     * @param string $url
     * @param array|null $body
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function putJson(string $url, array $body = null, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->put($url, [
            'headers' => $headers,
            'json' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }

    /**
     * Sends a PUT request with multipart
     *
     * @param string $url
     * @param array|null $body
     * @param array $headers
     * @return mixed
     * @throws GuzzleException
     */
    public function putMultiPart(string $url, array $body = null, array $headers = [])
    {
        $this->httpResponse = $this->httpClient->put($url, [
            'headers' => $headers,
            'multipart' => $body,
        ]);

        return $this->httpResponse->getBody()->getStreamContents();
    }
}
