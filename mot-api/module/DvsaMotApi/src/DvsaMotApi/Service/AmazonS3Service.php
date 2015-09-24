<?php

namespace DvsaMotApi\Service;
use DvsaCommonApi\Service\Exception\NotFoundException;


/**
 * Class AmazonS3Service
 *
 * @package DvsaMotApi\Service
 */
class AmazonS3Service
{
    private $s3;
    private $storageBucket = null;
    private $signedUrlTimeout = '+ 10 Minutes';

    /**
     * @param $config
     * @param AmazonSDKService $s3
     */
    public function __construct($config, AmazonSDKService $s3)
    {
        if (isset($config['aws']['URLTimeout'])) {
            $this->signedUrlTimeout = $config['aws']['URLTimeout'];
        }

        if (isset($config['aws']['certificateStorage']['bucket'])) {
            $this->storageBucket = $config['aws']['certificateStorage']['bucket'];
        }

        $this->s3 = $s3->getS3Client();
    }

    private function getStorageBucket()
    {
        if (null === $this->storageBucket) {
            throw new \Exception("No S3 storage bucket found within application config");
        }

        return $this->storageBucket;
    }

    private function getUrlTimeout()
    {
        return $this->signedUrlTimeout;
    }

    /**
     * @param $storageKey
     * @return \Psr\Http\Message\RequestInterface
     * @throws NotFoundException
     */
    public function getSignedUrlByKey($storageKey)
    {
        if (!$this->s3->doesObjectExist($this->storageBucket, $storageKey)) {
            throw new NotFoundException('File with storage key ' . $storageKey);
        }

        $command = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->getStorageBucket(),
            'Key' => $storageKey,
        ]);

        return $this->s3->createPresignedRequest($command, $this->getUrlTimeout());
    }

    /**
     * @param $storageKey
     * @return \Aws\ResultInterface|mixed
     * @throws NotFoundException
     */
    public function getObject($storageKey)
    {
        if (!$this->s3->doesObjectExist($this->storageBucket, $storageKey)) {
            throw new NotFoundException('File with storage key ' . $storageKey);
        }

        $command = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->getStorageBucket(),
            'Key' => $storageKey,
        ]);

        return $this->s3->execute($command, $this->getUrlTimeout());
    }

}
