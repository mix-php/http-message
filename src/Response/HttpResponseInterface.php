<?php

namespace Mix\Http\Message\Response;

/**
 * Interface HttpResponseInterface
 * @package Mix\Http\Message\Response
 * @author liu,jian <coder.keda@gmail.com>
 */
interface HttpResponseInterface
{

    /**
     * 格式值
     */
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    const FORMAT_XML = 'xml';
    const FORMAT_RAW = 'raw';

    /**
     * 设置响应格式
     * @param string $format
     */
    public function setFormat($format);

    /**
     * 设置状态码
     * @param int $statusCode
     */
    public function setStatusCode($statusCode);

    /**
     * 设置响应内容
     * @param string $content
     */
    public function setContent($content);

    /**
     * 设置Header信息
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value);

    /**
     * 设置全部Header信息
     * @param array $headers
     */
    public function setHeaders($headers);

    /**
     * 设置Cookie
     * @param $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function setCookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $httpOnly = false);

    /**
     * 重定向
     * @param $url
     */
    public function redirect($url);

    /**
     * 发送
     */
    public function send();

}
