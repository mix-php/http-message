<?php

namespace Mix\Http\Message\Request;

use Mix\Core\Component\ComponentInterface;

/**
 * Class HttpRequest
 * @package Mix\Http\Message\Request
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpRequest extends AbstractHttpRequest implements HttpRequestInterface
{

    /**
     * 请求者
     * @var \Swoole\Http\Request
     */
    protected $_requester;

    /**
     * 文件描述符
     * @var int
     */
    public $fd;

    /**
     * 针对每个请求执行初始化
     * @param \Swoole\Http\Request $request
     */
    public function beforeInitialize(\Swoole\Http\Request $request)
    {
        // 设置请求者
        $this->_requester = $request;
        // 执行初始化
        $this->setRoute([]);
        $this->_get    = isset($request->get) ? $request->get : [];
        $this->_post   = isset($request->post) ? $request->post : [];
        $this->_files  = isset($request->files) ? $request->files : [];
        $this->_cookie = isset($request->cookie) ? $request->cookie : [];
        $this->_server = isset($request->server) ? $request->server : [];
        $this->_header = isset($request->header) ? $request->header : [];
        $this->fd      = $request->fd;
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
     * 返回原始的HTTP包体
     * @return string
     */
    public function getRawBody()
    {
        return $this->_requester->rawContent();
    }

}
