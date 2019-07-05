<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\Request;
use Mix\Http\Message\Stream\ContentStream;
use Mix\Http\Message\Uri\Uri;
use Psr\Http\Message\RequestInterface;

/**
 * Class RequestFactory
 * @package Mix\Http\Message\Factory
 * @author liu,jian <coder.keda@gmail.com>
 */
class RequestFactory
{

    /**
     * Create a new RequestInterface.
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
        return new Request([
            'protocolVersion' => $protocolVersion,
            'headers'         => $headers,
            'body'            => $body,
            'requestTarget'   => '/',
            'method'          => $method,
            'uri'             => $uri,
        ]);
    }

}
