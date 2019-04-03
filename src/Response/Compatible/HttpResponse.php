<?php

namespace Mix\Http\Message\Response\Compatible;

use Mix\Http\Message\Response\AbstractHttpResponse;
use Mix\Http\Message\Response\HttpResponseInterface;

/**
 * Class HttpResponse
 * @package Mix\Http\Message\Compatible
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpResponse extends AbstractHttpResponse implements HttpResponseInterface
{

    /**
     * 初始化事件
     */
    public function onInitialize()
    {
        parent::onInitialize();
        // 初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->format     = $this->defaultFormat;
        $this->statusCode = 200;
        $this->content    = '';
        $this->headers    = [];
        $this->_isSent    = false;
    }

    /**
     * 设置Cookie
     * @param $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function setCookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        return setcookie($name, $value, $expires, $path, $domain, $secure, $httpOnly);
    }

    /**
     * 重定向
     * @param $url
     */
    public function redirect($url)
    {
        $this->setHeader('Location', $url);
    }

    /**
     * 发送
     */
    public function send()
    {
        // 多次发送处理
        if ($this->_isSent) {
            return;
        }
        $this->_isSent = true;
        // 预处理
        $this->prepare();
        // 发送
        $this->sendStatusCode();
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     * 发送HTTP状态码
     */
    protected function sendStatusCode()
    {
        header("HTTP/1.1 {$this->statusCode}");
    }

    /**
     * 发送Header信息
     */
    protected function sendHeaders()
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
    }

    /**
     * 发送内容
     */
    protected function sendContent()
    {
        // 非标量处理
        if (!is_scalar($this->content)) {
            $this->content = ucfirst(gettype($this->content));
        }
        // 发送内容
        echo $this->content;
    }

}
