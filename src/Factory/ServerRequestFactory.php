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
        if (!is_string($uri)) {
            $uri = (new UriFactory())->createUri($uri);
        }
        return new ServerRequest($method, $uri, $serverParams);
    }

    /**
     * Create a new server request.
     *
     * @param \Swoole\Http\Request $req
     * @return ServerRequestInterface
     */
    public function createServerRequestFromSwoole(\Swoole\Http\Request $req): ServerRequestInterface
    {
        list($scheme, $protocolVersion) = explode('/', $req->server['server_protocol']);
        $method       = $req->server['request_method'] ?? '';
        $scheme       = strtolower($scheme);
        $host         = $req->header['host'] ?? '';
        $requestUri   = $req->server['request_uri'] ?? '';
        $queryString  = $req->server['query_string'] ?? '';
        $uri          = new Uri($scheme . '://' . $host . $requestUri . ($queryString ? "?{$queryString}" : ''));
        $serverParams = $req->server ?? [];

        $serverRequest = $this->createServerRequest($method, $uri, $serverParams);

        $headers = $req->header ?? [];
        foreach ($headers as $name => $value) {
            $serverRequest->withHeader($name, $value);
        }

        $contentType = $serverRequest->getHeaderLine('content-type');
        $content     = '';
        if (strpos($contentType, 'multipart/form-data') === false) {
            $content = $req->rawContent();
        }
        $body = (new StreamFactory())->createStream($content);
        $serverRequest->withBody($body);

        $cookieParams = $req->cookie ?? [];
        $serverRequest->withCookieParams($cookieParams);

        $queryParams = $req->get ?? [];
        $serverRequest->withQueryParams($queryParams);

        $uploadedFiles       = [];
        $uploadedFileFactory = new UploadedFileFactory;
        $streamFactory       = new StreamFactory();
        foreach ($req->files ?? [] as $name => $file) {
            // 注意：当httpServer的handle内开启协程时，handle方法会先于Callback执行完，这时临时文件会在还没处理完成就被删除，所以这里生成新文件，在UploadedFile析构时删除该文件
            $tmpFile = $file['tmp_name'] . '.mix';
            move_uploaded_file($file['tmp_name'], $tmpFile);
            $uploadedFiles[$name] = $uploadedFileFactory->createUploadedFile(
                $streamFactory->createStreamFromFile($tmpFile),
                $file['size'],
                $file['error'],
                $file['name'],
                $file['type']
            );
        }
        $serverRequest->withUploadedFiles($uploadedFiles);

        $parsedBody = $req->post ?? [];
        $serverRequest->withParsedBody($parsedBody);

        return $serverRequest;
    }

}
