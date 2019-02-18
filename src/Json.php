<?php

namespace Mix\Http\Message;

use Mix\Core\Bean\BeanObject;
use Mix\Helpers\JsonHelper;

/**
 * JSON 类
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Json extends BeanObject
{

    // 编码
    public static function encode($data)
    {
        // 不转义中文、斜杠
        return JsonHelper::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

}
