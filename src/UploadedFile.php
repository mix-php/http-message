<?php

namespace Mix\Http\Message;

use Mix\Helper\RandomStringHelper;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class UploadedFile
 * @package Mix\Http\Message
 * @author liu,jian <coder.keda@gmail.com>
 */
class UploadedFile implements UploadedFileInterface
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
     * Retrieve a stream representing the uploaded file.
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native PHP
     * stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in a
     * native PHP stream wrapper to work with such functions).
     *
     * If the moveTo() method has been called previously, this method MUST raise
     * an exception.
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws \RuntimeException in cases when no stream is available or can be
     *     created.
     */
    public function getStream();

    /**
     * Move the uploaded file to a new location.
     *
     * Use this method as an alternative to move_uploaded_file(). This method is
     * guaranteed to work in both SAPI and non-SAPI environments.
     * Implementations must determine which environment they are in, and use the
     * appropriate method (move_uploaded_file(), rename(), or a stream
     * operation) to perform the operation.
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     *
     * The original file or stream MUST be removed on completion.
     *
     * If this method is called more than once, any subsequent calls MUST raise
     * an exception.
     *
     * When used in an SAPI environment where $_FILES is populated, when writing
     * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
     * used to ensure permissions and upload status are verified correctly.
     *
     * If you wish to move to a stream, use getStream(), as SAPI operations
     * cannot guarantee writing to stream destinations.
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath Path to which to move the uploaded file.
     * @throws \InvalidArgumentException if the $targetPath specified is invalid.
     * @throws \RuntimeException on any error during the move operation, or on
     *     the second or subsequent call to the method.
     */
    public function moveTo($targetPath);

    /**
     * Retrieve the file size.
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize();

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError();

    /**
     * Retrieve the filename sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     *
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename();

    /**
     * Retrieve the media type sent by the client.
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType();


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
