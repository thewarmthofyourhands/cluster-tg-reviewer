<?php

declare(strict_types=1);

namespace App\Queue;

use Jobcloud\Kafka\Consumer\KafkaConsumerBuilder;
use Jobcloud\Kafka\Consumer\KafkaConsumerInterface;
use Jobcloud\Kafka\Exception\KafkaConsumerConsumeException;
use Jobcloud\Kafka\Exception\KafkaConsumerEndOfPartitionException;
use Jobcloud\Kafka\Exception\KafkaConsumerTimeoutException;
use Jobcloud\Kafka\Message\KafkaConsumerMessage;

readonly class KafkaConsumerQueue
{
    private KafkaConsumerInterface $consumer;

    public function __construct(
        private string $brokerUrl,
    ) {}

    public function createSubscriber(string $topic, string $consumerGroup): void
    {
        $consumer = KafkaConsumerBuilder::create()
            ->withAdditionalConfig(
                [
                    'compression.codec' => 'lz4',
                    'auto.commit.interval.ms' => 500,
                ]
            )
            ->withAdditionalBroker($this->brokerUrl)
            ->withConsumerGroup($consumerGroup)
            ->withAdditionalSubscription($topic)
            ->build();
        $consumer->subscribe();
        $this->consumer = $consumer;
    }

    public function consume(): null|KafkaConsumerMessage
    {
        try {
            $message = $this->consumer->consume();
            $this->consumer->commit($message);

            return $message;
        } catch (KafkaConsumerTimeoutException $e) {
            return null;
        } catch (KafkaConsumerEndOfPartitionException $e) {
            return null;
        } catch (KafkaConsumerConsumeException $e) {
            throw $e;
        }
    }
}
