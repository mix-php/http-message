<?php

namespace Mix\Http\Message\Factory;

use Mix\Http\Message\Message;
use Mix\Http\Message\Stream\ContentStream;
use Psr\Http\Message\MessageInterface;

/**
 * Class MessageFactory
 * @package Mix\Http\Message\Factory
 */
class MessageFactory
{

    /**
     * Create a new MessageInterface.
     * 
     * @param \Swoole\Http\Request $request
     * @return MessageInterface
     */
    public static function createFromSwoole(\Swoole\Http\Request $request): MessageInterface
    {
        $serserProtocol = explode('/', $request->server['server_protocol']);
        list(, $protocolVersion) = $serserProtocol;
        $headers = $request->header ?? [];
        $body    = new ContentStream($request->rawContent());
        return new Message([
            'protocolVersion' => $protocolVersion,
            'headers'         => $headers,
            'body'            => $body,
        ]);
    }

}
