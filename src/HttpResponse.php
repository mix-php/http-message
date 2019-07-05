<?php

namespace Mix\Http\Message;

use Mix\Bean\BeanInjector;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponse
 * @package Mix\Http\Message
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpResponse extends HttpMessage implements ResponseInterface
{

    /**
     * @var \Swoole\Http\Response
     */
    public $responder;

    /**
     * @var int
     */
    public $statusCode = 200;

    /**
     * @var string
     */
    public $reasonPhrase = '';

    /**
     * @var Cookie[]
     */
    public $cookies = [];

    /**
     * HttpResponse constructor.
     * @param array $config
     * @throws \PhpDocReader\AnnotationException
     * @throws \ReflectionException
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->statusCode   = $code;
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * 返回cookies
     * @return Cookie[]
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * 设置cookies
     * @param Cookie[] $cookies
     * @return static
     */
    public function withCookies(array $cookies)
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * 设置cookie
     * @param $name
     * @param $value
     * @return static
     */
    public function withCookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;
        return $this;
    }

    public function send()
    {
        $headers = $this->getHeaders();
        foreach ($headers as $name => $value) {
            $this->responder->header($name, $value);
        }
        $cookies = $this->getCookies();
        foreach ($cookies as $cookie) {
            $this->responder->cookie($cookie->getName(), $cookie->getValue(), $cookie->);
        }
    }

}
