<?php

declare(strict_types=1);

namespace App\Queue;

use Jobcloud\Kafka\Message\KafkaProducerMessage;
use Jobcloud\Kafka\Producer\KafkaProducerBuilder;

readonly class KafkaProduceQueue
{
    private const PRODUCE_FLUSH_TIMEOUT = 20*1000;

    public function __construct(
        private string $brokerUrl,
    ) {}

    public function produce(string $topicName, int $partition, array $messageList): void
    {
        $producer = KafkaProducerBuilder::create()
            ->withAdditionalBroker($this->brokerUrl)
            ->withAdditionalConfig([
                'enable.idempotence' => 'true',
            ])
            ->build();

        foreach ($messageList as $messageData) {
            $message = KafkaProducerMessage::create($topicName, $partition)
                ->withKey($messageData['key'] ?? null)
                ->withBody($messageData['body'])
                ->withHeaders($messageData['headers'] ?? null);
            $producer->produce($message);
        }

        for ($flushRetries = 0; $flushRetries < 2; $flushRetries++) {
            $result = $producer->flush(self::PRODUCE_FLUSH_TIMEOUT);

            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                return;
            }
        }

        throw new \RuntimeException('Was unable to flush, messages might be lost!', $result);
    }
}
