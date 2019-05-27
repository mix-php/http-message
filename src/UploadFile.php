<?php

namespace Mix\Http\Message;

use Mix\Helper\RandomStringHelper;

/**
 * Class UploadFile
 * @package Mix\Http\Message
 * @author liu,jian <coder.keda@gmail.com>
 */
class UploadFile
{

    /**
     * 文件名
     * @var string
     */
    public $name;

    /**
     * MIME类型
     * @var string
     */
    public $type;

    /**
     * 临时文件
     * @var string
     */
    public $tmpName;

    /**
     * 错误码
     * @var int
     */
    public $error;

    /**
     * 文件尺寸
     * @var int
     */
    public $size;

    /**
     * 创建实例，通过表单名称
     * @param $name
     * @return $this
     */
    public static function newInstance($name)
    {
        $file = \Mix::$app->request->files($name);
        return is_null($file) ? $file : new static($file);
    }

    /**
     * UploadFile constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->name    = $file['name'];
        $this->type    = $file['type'];
        $this->tmpName = $file['tmp_name'];
        $this->error   = $file['error'];
        $this->size    = $file['size'];
    }

    /**
     * 文件另存为
     * @param $filename
     * @return bool
     */
    public function saveAs($filename)
    {
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $bytes = file_put_contents($filename, file_get_contents($this->tmpName));
        return $bytes ? true : false;
    }

    /**
     * 获取基础名称
     * @return mixed
     */
    public function getBaseName()
    {
        return pathinfo($this->name)['filename'];
    }

    /**
     * 获取扩展名
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->name)['extension'] ?? '';
    }

    /**
     * 获取随机文件名
     * @return string
     */
    public function getRandomFileName()
    {
        $extension = $this->getExtension();
        $extension = $extension ? ".{$extension}" : '';
        return md5(RandomStringHelper::randomAlphanumeric(32)) . $extension;
    }

}
