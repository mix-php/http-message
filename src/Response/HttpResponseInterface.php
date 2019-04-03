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
     * 设置Header信息
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value);

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
