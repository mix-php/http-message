<?php

namespace Mix\Http\Message;

use Mix\Bean\Object\AbstractObject;
use Mix\Helper\XmlHelper;

/**
 * Xml类
 * @author liu,jian <coder.keda@gmail.com>
 */
class Xml extends AbstractObject
{

    // 编码
    public function encode($data)
    {
        return XmlHelper::encode($data);
    }

}
