<?php

namespace Kanboard\Plugin\AliyunObjectStorageService;

require_once __DIR__.'/vendor/aliyun-oss-php-sdk/autoload.php';

use Kanboard\Core\ObjectStorage\ObjectStorageInterface;
use Kanboard\Core\ObjectStorage\ObjectStorageException;
use OSS\Core\OssException;
use OSS\OssClient;

class ObjectStorage implements ObjectStorageInterface
{
    /**
     * @var OssClient
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $bucket;

    /**
     * ObjectStorageService constructor.
     * @param $key
     * @param $secret
     * @param $endpoint
     * @param $bucket
     * @throws ObjectStorageException
     */
    public function __construct($key, $secret, $endpoint, $bucket)
    {
        $this->endpoint = $endpoint;
        $this->bucket = $bucket;

        try {
            $this->client = new OssClient($key, $secret, $this->endpoint);
        } catch (OssException $e) {
            throw new ObjectStorageException('Object not found');
        }
    }

    /**
     * Fetch object contents
     *
     * @access public
     * @param  string  $key
     * @return string
     * @throws  ObjectStorageException
     */
    public function get($key)
    {
        try {
            $hasFile = $this->client->doesObjectExist($this->bucket, $this->getObjectPath($key));
        } catch (OssException $e) {
            throw new ObjectStorageException('Object check fail');
        }

        if (!$hasFile) {
            throw new ObjectStorageException('Object not found');
        }

        try {
            $data = $this->client->getObject($this->bucket, $this->getObjectPath($key));
        } catch (OssException $e) {
            throw new ObjectStorageException('Object download fail');
        }

        return $data;
    }

    /**
     * @param string $key
     * @param string $blob
     * @return bool
     * @throws ObjectStorageException
     */
    public function put($key, &$blob)
    {
        try {
            $this->client->putObject($this->bucket, $this->getObjectPath($key), $blob);
        } catch (OssException $e) {
            throw new ObjectStorageException('Unable to save object');
        }

        return true;
    }

    /**
     * Output directly object content
     *
     * @access public
     * @param  string  $key
     */
    public function output($key)
    {
        try {
            if ($this->client->doesObjectExist($this->bucket, $this->getObjectPath($key))) {
                @readfile($this->getObjectUrl($key));
            }
        } catch (OssException $e) {}
    }

    /**
     * Move local file to object storage
     *
     * @access public
     * @param  string  $filename
     * @param  string  $key
     * @return boolean
     * @throws ObjectStorageException
     */
    public function moveFile($filename, $key)
    {
        if (!file_exists($filename)) {
            throw new ObjectStorageException('File does not exist');
        }

        try {
            if (is_file($filename)) {
                $this->client->uploadFile($this->bucket, $this->getObjectPath($key), $filename);
            } else {
                $this->client->putObject($this->bucket, $this->getObjectPath($key), file_get_contents($filename));
            }
        } catch (OssException $e) {
            throw new ObjectStorageException('Unable to upload file');
        }

        if (is_file($filename)) {
            unlink($filename);
        }

        return true;
    }

    /**
     * Move uploaded file to object storage
     * @param string $filename
     * @param string $key
     * @return bool
     * @throws ObjectStorageException
     */
    public function moveUploadedFile($filename, $key)
    {
        return $this->moveFile($filename, $key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove($key)
    {
        try {
            $this->client->deleteObject($this->bucket, $this->getObjectPath($key));
        } catch (OssException $e) {}

        return true;
    }

    private function getObjectPath($key)
    {
        return $key;
    }

    private function getObjectUrl($key)
    {
        return "https://{$this->bucket}.{$this->endpoint}.aliyuncs.com/{$key}";
    }
}
