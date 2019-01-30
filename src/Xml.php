<?php

namespace Mix\Http\Message;

use Mix\Core\BeanObject;
use Mix\Helpers\XmlHelper;

/**
 * Xml类
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class Xml extends BeanObject
{

    // 编码
    public function encode($data)
    {
        return XmlHelper::encode($data);
    }

}
