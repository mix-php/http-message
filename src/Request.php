<?php

namespace Mix\Http\Message;

/**
 * Request组件
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Request extends \Mix\Http\Message\Base\Request
{

    /**
     * 请求者
     * @var \Swoole\Http\Request
     */
    protected $_requester;

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
    }

    /**
     * 返回套接字描述符
     * @return int
     */
    public function getFileDescriptor()
    {
        return $this->_requester->fd;
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
