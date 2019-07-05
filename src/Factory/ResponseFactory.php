<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseFactory
 * @package Mix\Http\Message\Factory
 * @author liu,jian <coder.keda@gmail.com>
 */
class ResponseFactory
{

    /**
     * Create a new ResponseInterface.
     *
     * @param \Swoole\Http\Response $response
     * @return ResponseInterface
     */
    public static function createFromSwoole(\Swoole\Http\Response $response): ResponseInterface
    {
        return new Response([
            'responder' => $response,
        ]);
    }

}
