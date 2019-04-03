<?php

namespace Mix\Http\Message\Request\Compatible;

/**
 * Class HttpRequest
 * @package Mix\Http\Message\Request\Compatible
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpRequest extends \Mix\Http\Message\Request\Base\HttpRequest
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
        $this->_get    = $_GET;
        $this->_post   = $_POST;
        $this->_files  = $_FILES;
        $this->_cookie = $_COOKIE;
        $this->_header = $this->parseHeader();
        $this->_server = $this->parseServer();
    }

    /**
     * 解析 HEADER 值
     * @return array
     */
    protected function parseHeader()
    {
        $header = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $header[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
                unset($_SERVER[$name]);
            }
        }
        return $header;
    }

    /**
     * 解析 SERVER 值
     * @return array
     */
    protected function parseServer()
    {
        return array_change_key_case($_SERVER, CASE_LOWER);
    }

    /**
     * 返回原始的HTTP包体
     * @return bool|string
     */
    public function getRawBody()
    {
        return file_get_contents('php://input');
    }

}
