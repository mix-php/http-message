<?php

namespace Mix\Http\Message\Response;

use Mix\Core\Component\ComponentInterface;

/**
 * Class HttpResponse
 * @package Mix\Http\Message\Response
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpResponse extends \Mix\Http\Message\Response\Base\HttpResponse
{

    /**
     * 响应者
     * @var \Swoole\Http\Response
     */
    protected $_responder;

    /**
     * 针对每个请求执行初始化
     * @param \Swoole\Http\Response $response
     */
    public function beforeInitialize(\Swoole\Http\Response $response)
    {
        // 设置响应者
        $this->_responder = $response;
        // 执行初始化
        $this->format     = $this->defaultFormat;
        $this->statusCode = 200;
        $this->content    = '';
        $this->headers    = [];
        $this->_isSent    = false;
        // 设置组件状态
        $this->setStatus(ComponentInterface::STATUS_RUNNING);
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        // 移除设置组件状态
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
     */
    public function setCookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        return $this->_responder->cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly);
    }

    /**
     * 重定向
     * @param $url
     */
    public function redirect($url)
    {
        $this->setHeader('Location', $url);
        $this->statusCode = 302;
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
     * 发送 HTTP 状态码
     */
    protected function sendStatusCode()
    {
        $this->_responder->status($this->statusCode);
    }

    /**
     * 发送 Header 信息
     */
    protected function sendHeaders()
    {
        foreach ($this->headers as $key => $value) {
            $this->_responder->header($key, $value);
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
        $this->_responder->end($this->content);
    }

}
