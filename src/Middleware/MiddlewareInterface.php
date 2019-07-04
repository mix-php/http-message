<?php

namespace Mix\Http\Message\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewareInterface
 * @package Mix\Http\Message\Middleware
 * @author liu,jian <coder.keda@gmail.com>
 */
interface MiddlewareInterface
{

    /**
     * 处理
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, ResponseInterface $response, \Closure $next);

}
