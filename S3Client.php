<?php

require_once 'config/constants.php';
require_once 'config/aws-sdk/config.php';
require_once 'lib/amazon/sdk/sdk.class.php';
require_once 'lib/amazon/sdk/services/s3.class.php';

class S3Client
{
    /** @var AmazonS3 */
    private $client;

    /** @var string */
    private $bucket;

    /**
     * @param string $bucket
     */
    public function __construct($bucket = null)
    {
        $options = array(
            'key'    => AWS_KEY,
            'secret' => AWS_SECRET,
        );

        $this->client = new AmazonS3($options);
        $this->client->set_region(AmazonS3::REGION_EU_W1);

        $this->bucket = $bucket;
    }

    /**
     * @return string
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @param array $options
     * @return array
     */
    public function getObjectList(array $options)
    {
        return $this->client->get_object_list($this->bucket, $options);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getObjectUrl($fileName)
    {
        return $this->client->get_object_url($this->bucket, $fileName);
    }

    /**
     * @param array $source
     * @param array $destination
     * @return bool
     */
    public function copyObject(array $source, array $destination)
    {
        $response = $this->client->copy_object($source, $destination);

        return $response->isOK();
    }

    /**
     * @return string
     */
    public function getWebsiteEndpoint()
    {
        return sprintf('http://%s.s3-website-%s.amazonaws.com', $this->bucket, $this->client->get_bucket_region($this->bucket)->body);
    }
}
