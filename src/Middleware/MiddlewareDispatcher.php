<?php

namespace Mix\Http\Message\Middleware;

use Mix\Bean\BeanInjector;
use Mix\Http\Message\Exception\TypeException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MiddlewareDispatcher
 * @package Mix\Http\Message\Middleware
 * @author liu,jian <coder.keda@gmail.com>
 */
class MiddlewareDispatcher
{

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var array
     */
    public $middleware;

    /**
     * @var array
     */
    protected $objects = [];

    /**
     * MiddlewareDispatcher constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        BeanInjector::inject($this, $config);
        $this->init();
    }

    /**
     * 初始化
     */
    public function init()
    {
        $objects = [];
        foreach ($this->middleware as $key => $name) {
            $class  = "{$namespace}\\{$name}Middleware";
            $object = new $class();
            if (!($object instanceof MiddlewareInterface)) {
                throw new TypeException("{$class} type is not '" . MiddlewareInterface::class . "'");
            }
            $objects[$key] = $object;
        }
        $this->objects = $objects;
    }

    /**
     * 中间件调度
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var MiddlewareInterface $object */
        $object = array_shift($this->objects);
        if (empty($object)) {
            return $response;
        }
        return $object->process($request, $response, function () use ($request, $response) {
            return $this->dispatch($request, $response);
        });
    }

}
