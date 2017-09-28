<?php

require_once 'config/constants.php';
require_once 'config/aws-sdk/config.php';
require_once 'lib/amazon/sdk/sdk.class.php';
require_once 'lib/amazon/sdk/services/sqs.class.php';

abstract class AbstractSQSClient
{
    /** @var string */
    protected $queueName;

    /** @var AmazonSQS */
    protected $sqsClient;

    /**
     * SQSProducer constructor.
     * @param null|string $queueName
     */
    public function __construct($queueName = null)
    {
        $options = array(
            'key'    => AWS_KEY,
            'secret' => AWS_SECRET,
        );

        $this->sqsClient = new AmazonSQS($options);
        $this->sqsClient->set_region(AmazonSQS::REGION_EU_W1);

        $this->queueName = $queueName;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     */
    public function setQueueName($queueName)
    {
        $this->queueName = $queueName;
    }

    protected function getQueue()
    {
        $data = $this->sqsClient->get_queue_url($this->queueName);

        $queue = json_decode($data->body->to_json());

        if ($queue->Error) {
            throw new Exception(sprintf('AWS error: %s', $queue->Error->Message), 404);
        }

        return $queue;
    }
}
