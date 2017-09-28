<?php

require_once 'lib/amazon/AbstractSQSClient.php';

class SQSProducer extends AbstractSQSClient
{
    public function publish($message)
    {
        $queue = $this->getQueue();

        return $this->sqsClient->send_message($queue->GetQueueUrlResult->QueueUrl, json_encode($message));
    }

    public function listQueue($pcre)
    {
        return $this->sqsClient->get_queue_list($pcre);
    }
}
