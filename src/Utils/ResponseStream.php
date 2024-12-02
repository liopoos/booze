<?php

namespace Liopoos\Booze\Utils;

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use Psr\Http\Message\StreamInterface;

class ResponseStream implements StreamInterface
{
    use StreamDecoratorTrait;

    protected $contentType;

    public function __construct(StreamInterface $stream, $contentType)
    {
        $this->stream = $stream;
        $this->contentType = $contentType;
    }

    /**
     * Get response stream content
     * @return mixed
     */
    public function getStreamContents()
    {
        $contents = $this->getContents();

        if (false !== stripos($this->contentType, 'json') || stripos($this->contentType, 'javascript')) {
            $contents = json_decode($contents, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error decode response content to json: ' . json_last_error_msg());
            }

            return $contents;
        } elseif (false !== stripos($this->contentType, 'xml')) {
            return json_decode(json_encode(simplexml_load_string($contents)), true);
        }

        return $contents;
    }
}
