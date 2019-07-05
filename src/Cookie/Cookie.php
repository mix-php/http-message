<?php

namespace Mix\Http\Message\Cookie;

/**
 * Class Cookie
 * @package Mix\Http\Message\Cookie
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
     * @return string
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
     * Get domain
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
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
     * Get httpOnly
     * @return string
     */
    public function getHttpOnly()
    {
        return $this->name;
    }

}
