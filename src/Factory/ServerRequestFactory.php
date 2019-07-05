<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\ServerRequest;
use Mix\Http\Message\Stream\ContentStream;
use Mix\Http\Message\Stream\FileStream;
use Mix\Http\Message\Upload\UploadedFile;
use Mix\Http\Message\Uri\Uri;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ServerRequestFactory
 * @package Mix\Http\Message\Factory
 */
class ServerRequestFactory
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
    public static function createFromSwoole(\Swoole\Http\Request $request): ServerRequestInterface
    {
        $serserProtocol = explode('/', $request->server['server_protocol']);
        list($scheme, $protocolVersion) = $serserProtocol;
        $scheme        = strtolower($scheme);
        $headers       = $request->header ?? [];
        $body          = new ContentStream($request->rawContent());
        $method        = $request->server['request_method'] ?? '';
        $host          = $request->header['host'] ?? '';
        $requestUri    = $request->server['request_uri'] ?? '';
        $queryString   = $request->server['query_string'] ?? '';
        $uri           = new Uri($scheme . '://' . $host . $requestUri . ($queryString ? "?{$queryString}" : ''));
        $serverParams  = $request->server ?? [];
        $cookieParams  = $request->cookie ?? [];
        $queryParams   = $request->get ?? [];
        $uploadedFiles = [];
        foreach ($request->files ?? [] as $name => $file) {
            $uploadedFiles[] = new UploadedFile([
                'stream'          => new FileStream($file['tmp_name']),
                'error'           => $file['error'],
                'clientFilename'  => $file['tmp_name'],
                'clientMediaType' => $file['type'],
            ]);
        }
        $parsedBody = $request->post ?? [];
        return new ServerRequest([
            'protocolVersion' => $protocolVersion,
            'headers'         => $headers,
            'body'            => $body,
            'requestTarget'   => '/',
            'method'          => $method,
            'uri'             => $uri,
            'serverParams'    => $serverParams,
            'cookieParams'    => $cookieParams,
            'queryParams'     => $queryParams,
            'uploadedFiles'   => $uploadedFiles,
            'parsedBody'      => $parsedBody,
        ]);
    }

}
