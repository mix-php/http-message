<?php

namespace Mix\Http\Message\Cookie;

/**
 * Class Cookie
 * @package Mix\Http\Message\Cookie
 * @author liu,jian <coder.keda@gmail.com>
 */
class Cookie
{

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $value = '';

    /**
     * @var int
     */
    public $expire = 0;

    /**
     * @var string
     */
    public $path = '/';

    /**
     * @var string
     */
    public $domain = '';

    /**
     * @var bool
     */
    public $secure = false;

    /**
     * @var bool
     */
    public $httpOnly = false;

    /**
     * Cookie constructor.
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     */
    public function __construct(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false)
    {
        $this->name     = $name;
        $this->value    = $value;
        $this->expire   = $expire;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->secure   = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get value
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get expire
     * @return int
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Get path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * With the specified path
     * @param string $path
     * @return $this
     */
    public function withPath(string $path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get domain
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * With the specified domain
     * @param string $domain
     * @return $this
     */
    public function withDomain(string $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get secure
     * @return string
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * With the specified secure
     * @param bool $secure
     * @return $this
     */
    public function withSecure(bool $secure)
    {
        $this->secure = $secure;
        return $this;
    }

    /**
     * Get httpOnly
     * @return string
     */
    public function getHttpOnly()
    {
        return $this->name;
    }

    /**
     * With the specified httpOnly
     * @param bool $httpOnly
     * @return $this
     */
    public function withHttpOnly(bool $httpOnly)
    {
        $this->httpOnly = $httpOnly;
        return $this;
    }

}
