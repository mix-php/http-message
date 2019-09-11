<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\ServerRequest;
use Mix\Http\Message\Stream\ContentStream;
use Mix\Http\Message\Stream\FileStream;
use Mix\Http\Message\Upload\UploadedFile;
use Mix\Http\Message\Uri\Uri;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ServerRequestFactory
 * @package Mix\Http\Message\Factory
 * @author liu,jian <coder.keda@gmail.com>
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{

    /**
     * Create a new server request.
     *
     * Note that server-params are taken precisely as given - no parsing/processing
     * of the given values is performed, and, in particular, no attempt is made to
     * determine the HTTP method or URI, which must be provided explicitly.
     *
     * @param string $method The HTTP method associated with the request.
     * @param UriInterface|string $uri The URI associated with the request. If
     *     the value is a string, the factory MUST create a UriInterface
     *     instance based on it.
     * @param array $serverParams Array of SAPI parameters with which to seed
     *     the generated request instance.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (is_string($uri)) {
            $uri = (new UriFactory())->createUri($uri);
        }
        return new ServerRequest($method, $uri, $serverParams);
    }

    /**
     * Create a new server request.
     *
     * @param \Swoole\Http\Request $requ
     * @return ServerRequestInterface
     */
    public function createServerRequestFromSwoole(\Swoole\Http\Request $requ): ServerRequestInterface
    {
        list($scheme, $protocolVersion) = explode('/', $requ->server['server_protocol']);
        $method       = $requ->server['request_method'] ?? '';
        $scheme       = strtolower($scheme);
        $host         = $requ->header['host'] ?? '';
        $requestUri   = $requ->server['request_uri'] ?? '';
        $queryString  = $requ->server['query_string'] ?? '';
        $uri          = $scheme . '://' . $host . $requestUri . ($queryString ? "?{$queryString}" : '');
        $serverParams = $requ->server ?? [];

        /** @var ServerRequest $serverRequest */
        $serverRequest = $this->createServerRequest($method, $uri, $serverParams);
        $serverRequest->withSwooleRequest($requ);
        $serverRequest->withRequestTarget($uri);

        $headers = $requ->header ?? [];
        foreach ($headers as $name => $value) {
            $serverRequest->withHeader($name, $value);
        }

        $contentType = $serverRequest->getHeaderLine('content-type');
        $content     = '';
        if (
            strpos($contentType, 'multipart/form-data') === false
            &&
            strpos($contentType, 'application/x-www-form-urlencoded') === false
        ) {
            $content = $requ->rawContent();
        }
        $body = (new StreamFactory())->createStream($content);
        $serverRequest->withBody($body);

        $cookieParams = $requ->cookie ?? [];
        $serverRequest->withCookieParams($cookieParams);

        $queryParams = $requ->get ?? [];
        $serverRequest->withQueryParams($queryParams);

        $uploadedFiles       = [];
        $uploadedFileFactory = new UploadedFileFactory;
        $streamFactory       = new StreamFactory();
        foreach ($requ->files ?? [] as $name => $file) {
            // 注意：当httpServer的handle内开启协程时，handle方法会先于Callback执行完，
            // 这时临时文件会在还没处理完成就被删除，所以这里生成新文件，在UploadedFile析构时删除该文件
            $tmpfile = $file['tmp_name'] . '.mix';
            move_uploaded_file($file['tmp_name'], $tmpfile);
            $uploadedFiles[$name] = $uploadedFileFactory->createUploadedFile(
                $streamFactory->createStreamFromFile($tmpfile),
                $file['size'],
                $file['error'],
                $file['name'],
                $file['type']
            );
        }
        $serverRequest->withUploadedFiles($uploadedFiles);

        $parsedBody = $requ->post ?? [];
        $serverRequest->withParsedBody($parsedBody);

        return $serverRequest;
    }

}
