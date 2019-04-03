<?php

namespace Mix\Http\Message\Request\Base;

use Mix\Core\Component\AbstractComponent;
use Mix\Http\Message\Request\HttpRequestInterface;

/**
 * Class HttpRequest
 * @package Mix\Http\Message\Request\Base
 * @author liu,jian <coder.keda@gmail.com>
 */
class HttpRequest extends AbstractComponent implements HttpRequestInterface
{

    /**
     * ROUTE 参数
     * @var array
     */
    protected $_route = [];

    /**
     * GET 参数
     * @var array
     */
    protected $_get = [];

    /**
     * POST 参数
     * @var array
     */
    protected $_post = [];

    /**
     * FILES 参数
     * @var array
     */
    protected $_files = [];

    /**
     * COOKIE 参数
     * @var array
     */
    protected $_cookie = [];

    /**
     * SERVER 参数
     * @var array
     */
    protected $_server = [];

    /**
     * HEADER 参数
     * @var array
     */
    protected $_header = [];

    /**
     * 设置 ROUTE 值
     * @param $route
     */
    public function setRoute($route)
    {
        $this->_route = $route;
    }

    /**
     * 提取 GET 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function get($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_get);
    }

    /**
     * 提取 POST 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function post($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_post);
    }

    /**
     * 提取 FILES 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function files($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_files);
    }

    /**
     * 提取 ROUTE 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function route($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_route);
    }

    /**
     * 提取 COOKIE 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function cookie($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_cookie);
    }

    /**
     * 提取 SERVER 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function server($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_server);
    }

    /**
     * 提取 HEADER 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function header($name = null, $default = null)
    {
        return static::fetch($name, $default, $this->_header);
    }

    /**
     * 提取数据
     * @param $name
     * @param $default
     * @param $container
     * @return mixed
     */
    protected static function fetch($name, $default, $container)
    {
        return is_null($name) ? $container : (isset($container[$name]) ? $container[$name] : $default);
    }

    /**
     * 是否为 GET 请求
     * @return bool
     */
    public function isGet()
    {
        return $this->method() == 'GET';
    }

    /**
     * 是否为 POST 请求
     * @return bool
     */
    public function isPost()
    {
        return $this->method() == 'POST';
    }

    /**
     * 是否为 PUT 请求
     * @return bool
     */
    public function isPut()
    {
        return $this->method() == 'PUT';
    }

    /**
     * 是否为 PATCH 请求
     * @return bool
     */
    public function isPatch()
    {
        return $this->method() == 'PATCH';
    }

    /**
     * 是否为 DELETE 请求
     * @return bool
     */
    public function isDelete()
    {
        return $this->method() == 'DELETE';
    }

    /**
     * 是否为 HEAD 请求
     * @return bool
     */
    public function isHead()
    {
        return $this->method() == 'HEAD';
    }

    /**
     * 是否为 OPTIONS 请求
     * @return bool
     */
    public function isOptions()
    {
        return $this->method() == 'OPTIONS';
    }

    /**
     * 返回请求类型
     * @return mixed
     */
    public function method()
    {
        return $this->server('request_method');
    }

    /**
     * 返回请求的根URL
     * @return string
     */
    public function root()
    {
        return $this->scheme() . '://' . $this->header('host');
    }

    /**
     * 返回请求的路径
     * @return bool|string
     */
    public function path()
    {
        return substr($this->server('path_info'), 1);
    }

    /**
     * 返回请求的URL
     * @return string
     */
    public function url()
    {
        return $this->scheme() . '://' . $this->header('host') . $this->server('path_info');
    }

    /**
     * 返回请求的完整URL
     * @return string
     */
    public function fullUrl()
    {
        return $this->scheme() . '://' . $this->header('host') . $this->server('path_info') . '?' . $this->server('query_string');
    }

    /**
     * 获取协议
     * @return mixed
     */
    protected function scheme()
    {
        return $this->server('request_scheme') ?: $this->header('scheme');
    }

}
