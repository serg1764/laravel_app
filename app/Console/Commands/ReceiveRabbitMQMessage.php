<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ReceiveRabbitMQMessage extends Command
{

    protected $signature = 'rabbitmq:receive';

    /**
     * Получаем тестовое сообщение из очереди.
     * Запускаем командой
     * php artisan rabbitmq:receive
     *
     */

    protected $description = 'Получаем сообщение из очереди RabbitMQ очередь - hello';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->info("[*] Waiting for messages. To exit press CTRL+C");

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
        $channel = $connection->channel();

        // Очередь объявляем один раз. Если она есть, то эта строчка не нужна.
        $channel->queue_declare('hello', false, false, false, false);

        $callback = function ($msg) {
            $this->info(" [x] Received " . $msg->getBody());
        };

        /** Если кратко: мы подписываемся на очередь hello, и всё, что придёт в неё, будет передано в $callback.*/
        $channel->basic_consume(
            'hello',    // queue: имя очереди, которую слушаем
            '',   // consumer_tag: можно оставить пустым — RabbitMQ сам назначит ID
            false,    // no_local: true — не получать свои же сообщения (для pub/sub, обычно false)
            true,      // no_ack: true — автоподтверждение (всё, что получено, считается обработанным)
            false,   // exclusive: true — только этот consumer может читать из очереди
            false,     // nowait: false — ждать подтверждения от брокера
            $callback       // callback: функция, вызываемая при получении каждого сообщения
        );

        try {

            /** is_consuming() проверяет, есть ли активные подписки (consumers)  */
            while ($channel->is_consuming()) {

                /** wait() — блокирует выполнение, ждёт новое сообщение.
                 * Когда сообщение приходит, вызывается твой $callback */
                $channel->wait();
            }
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
        }

        $channel->close();
        $connection->close();
    }
}
