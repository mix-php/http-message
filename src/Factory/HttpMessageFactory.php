<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\HttpMessage;
use Mix\Http\Message\Stream\ContentStream;
use Psr\Http\Message\MessageInterface;

/**
 * Class HttpMessageFactory
 * @package Mix\Http\Message\Factory
 */
class HttpMessageFactory
{

    /**
     * Create a new request.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request. If
     *     the value is a string, the factory MUST create a UriInterface
     *     instance based on it.
     *
     * @param \Swoole\Http\Request $request
     * @return MessageInterface
     */
    public static function createFromSwoole(\Swoole\Http\Request $request): MessageInterface
    {
        $serserProtocol = explode('/', $request->server['server_protocol']);
        list(, $protocolVersion) = $serserProtocol;
        $headers = $request->header ?? [];
        $body    = new ContentStream($request->rawContent());
        return new HttpMessage([
            'protocolVersion' => $protocolVersion,
            'headers'         => $headers,
            'body'            => $body,
        ]);
    }

}
