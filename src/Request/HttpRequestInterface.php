<?php

namespace Mix\Http\Message\Request;

/**
 * Interface HttpRequestInterface
 * @package Mix\Http\Message\Request
 * @author liu,jian <coder.keda@gmail.com>
 */
interface HttpRequestInterface
{

    /**
     * 提取 GET 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function get($name = null, $default = null);

    /**
     * 提取 POST 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function post($name = null, $default = null);

    /**
     * 提取 FILES 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function files($name = null, $default = null);

    /**
     * 提取 ROUTE 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function route($name = null, $default = null);

    /**
     * 提取 COOKIE 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function cookie($name = null, $default = null);

    /**
     * 提取 SERVER 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function server($name = null, $default = null);

    /**
     * 提取 HEADER 值
     * @param null $name
     * @param null $default
     * @return mixed
     */
    public function header($name = null, $default = null);

    /**
     * 是否为 GET 请求
     * @return bool
     */
    public function isGet();

    /**
     * 是否为 POST 请求
     * @return bool
     */
    public function isPost();

    /**
     * 是否为 PUT 请求
     * @return bool
     */
    public function isPut();

    /**
     * 是否为 PATCH 请求
     * @return bool
     */
    public function isPatch();

    /**
     * 是否为 DELETE 请求
     * @return bool
     */
    public function isDelete();

    /**
     * 是否为 HEAD 请求
     * @return bool
     */
    public function isHead();

    /**
     * 是否为 OPTIONS 请求
     * @return bool
     */
    public function isOptions();

    /**
     * 返回请求类型
     * @return mixed
     */
    public function method();

    /**
     * 返回请求的根URL
     * @return string
     */
    public function root();

    /**
     * 返回请求的路径
     * @return bool|string
     */
    public function path();

    /**
     * 返回请求的URL
     * @return string
     */
    public function url();

    /**
     * 返回请求的完整URL
     * @return string
     */
    public function fullUrl();

    /**
     * 返回原始的HTTP包体
     * @return string
     */
    public function getRawBody();
    
}
