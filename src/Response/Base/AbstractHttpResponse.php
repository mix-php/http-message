<?php

namespace Mix\Http\Message\Response\Base;

use Mix\Core\Component\AbstractComponent;

/**
 * Class AbstractHttpResponse
 * @package Mix\Http\Message\Response
 * @author liu,jian <coder.keda@gmail.com>
 */
abstract class AbstractHttpResponse extends AbstractComponent
{

    /**
     * 默认输出格式
     * @var string
     */
    public $defaultFormat;

    /**
     * @var \Mix\Http\Message\Json
     */
    public $json;

    /**
     * @var \Mix\Http\Message\Jsonp
     */
    public $jsonp;

    /**
     * @var \Mix\Http\Message\Xml
     */
    public $xml;

    /**
     * 当前输出格式
     * @var string
     */
    public $format;

    /**
     * 状态码
     * @var int
     */
    public $statusCode = 200;

    /**
     * 内容
     * @var string
     */
    public $content = '';

    /**
     * HTTP 响应头
     * @var array
     */
    public $headers = [];

    /**
     * 是否已经发送
     * @var bool
     */
    protected $_isSent = false;

    /**
     * 设置响应格式
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * 设置状态码
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * 设置响应内容
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * 设置Header信息
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * 设置全部Header信息
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * 预处理
     */
    protected function prepare()
    {
        // 设置默认 Content-Type 信息
        $headers = array_change_key_case($this->headers, CASE_LOWER);
        if (!isset($headers['content-type'])) {
            switch ($this->format) {
                case static::FORMAT_HTML:
                    $this->setHeader('Content-Type', 'text/html; charset=utf-8');
                    break;
                case static::FORMAT_JSON:
                    $this->setHeader('Content-Type', 'application/json; charset=utf-8');
                    break;
                case static::FORMAT_JSONP:
                    $this->setHeader('Content-Type', 'application/json; charset=utf-8');
                    break;
                case static::FORMAT_XML:
                    $this->setHeader('Content-Type', 'text/xml; charset=utf-8');
                    break;
            }
        }
        // 转换内容为字符型
        $content = $this->content;
        is_null($content) and $content = '';
        if (is_array($content) || is_object($content)) {
            switch ($this->format) {
                case static::FORMAT_JSON:
                    $content = $this->json->encode($content);
                    break;
                case static::FORMAT_JSONP:
                    $content = $this->jsonp->encode($content);
                    break;
                case static::FORMAT_XML:
                    $content = $this->xml->encode($content);
                    break;
            }
        }
        $this->content = $content;
    }

}
