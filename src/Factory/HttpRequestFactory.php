<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\HttpRequest;
use Mix\Http\Message\Stream\ContentStream;
use Mix\Http\Message\Uri\Uri;
use Psr\Http\Message\RequestInterface;

/**
 * Class HttpRequestFactory
 * @package Mix\Http\Message\Factory
 */
class HttpRequestFactory
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
     * @return RequestInterface
     */
    public static function createFromSwoole(\Swoole\Http\Request $request): RequestInterface
    {
        $serserProtocol = explode('/', $request->server['server_protocol']);
        list($scheme, $protocolVersion) = $serserProtocol;
        $scheme      = strtolower($scheme);
        $headers     = $request->header ?? [];
        $body        = new ContentStream($request->rawContent());
        $method      = $request->server['request_method'] ?? '';
        $host        = $request->header['host'] ?? '';
        $requestUri  = $request->server['request_uri'] ?? '';
        $queryString = $request->server['query_string'] ?? '';
        $uri         = new Uri($scheme . '://' . $host . $requestUri . ($queryString ? "?{$queryString}" : ''));
        return new HttpRequest([
            'protocolVersion' => $protocolVersion,
            'headers'         => $headers,
            'body'            => $body,
            'requestTarget'   => '/',
            'method'          => $method,
            'uri'             => $uri,
        ]);
    }

}
