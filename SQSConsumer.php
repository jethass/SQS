<?php

require_once 'lib/amazon/AbstractSQSClient.php';

class SQSConsumer extends AbstractSQSClient
{
    /**
     * @return array
     */
    public function receive()
    {
        $queue = $this->getQueue();
        $parameters = array(
            'MaxNumberOfMessages' => 10
        );

        $data = $this->sqsClient->receive_message($queue->GetQueueUrlResult->QueueUrl, $parameters);
        $messages = json_decode($data->body->to_json());

        if (is_array($messages->ReceiveMessageResult->Message)) {
            $readableMessages = array();
            foreach ($messages->ReceiveMessageResult->Message as $sqsMessage) {
                $readableMessages[] = $this->prepareReadableMessage($sqsMessage);
            }

            return $readableMessages;
        }

        return array($this->prepareReadableMessage($messages->ReceiveMessageResult->Message));
    }

    /**
     * @param string $handle
     */
    public function deleteMessage($handle)
    {
        $queue = $this->getQueue();

        $this->sqsClient->delete_message($queue->GetQueueUrlResult->QueueUrl, $handle);
    }

    /**
     * @param $sqsMessage
     * @return array
     */
    private function prepareReadableMessage($sqsMessage)
    {
        return array(
            'body'          => json_decode($sqsMessage->Body, true),
            'receiptHandle' => $sqsMessage->ReceiptHandle,
        );
    }
}
