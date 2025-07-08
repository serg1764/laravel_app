<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ReceiveRabbitMQMessage2 extends Command
{

    protected $signature = 'rabbitmq:receive2';

    /**
     * Получаем тестовое сообщение из очереди.
     * Запускаем командой
     * php artisan rabbitmq:receive2
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
        $channel->queue_declare(
            'task_queue', // 🔹 1. Имя очереди. Здесь — 'task_queue'.
            // Если очередь с таким именем не существует, она будет создана.
            false,       // 🔹 2. Passive. false — создать очередь, если она не существует.
            // true — только проверить существование, не создавать.
            true,        // 🔹 3. Durable. true — очередь сохраняется при перезапуске брокера
            // (выживает рестарт RabbitMQ).
            false,      // 🔹 4. Exclusive. false — очередь доступна для других соединений.
            // true — доступна только текущему соединению и удаляется при его закрытии.
            false     // 🔹 5. Auto-delete. false — очередь не будет удалена автоматически,
        // когда последний потребитель отключится.
        );

        $callback = function (AMQPMessage $msg) {
            try {
                echo ' [x] Received ', $msg->getBody(), "\n";
                sleep(substr_count($msg->getBody(), '.'));
                echo " [x] Done\n";
                $msg->ack(); // Надо вызвать $msg->ack();, Чтобы RabbitMQ считал, что сообщение получено.
            } catch (\Throwable $e) {
                echo " [!] Error: ", $e->getMessage(), "\n";
                // Без ack — сообщение вернётся обратно
            }
        };

        $channel->basic_qos(
            null, // 🔹 1. prefetch_size — размер (в байтах) данных,
                            // которые может получить потребитель. null = отключено.
            1,      // 🔹 2. prefetch_count — количество сообщений,
                                // которые RabbitMQ "держит" за потребителем, не дождавшись ack.
                                // Значение 1 означает: "отправляй максимум одно сообщение за раз",
                                // следующее — только после ack.
            false   // 🔹 3. global — применять ограничение:
                            //     false = только к текущему потребителю (channel-level),
                            //true = ко всем потребителям на канале (channel-global).
        );

        /** Если кратко: мы подписываемся на очередь task_queue, и всё, что придёт в неё, будет передано в $callback.*/
        $channel->basic_consume(
            'task_queue',    // queue: имя очереди, которую слушаем
            '',   // consumer_tag: можно оставить пустым — RabbitMQ сам назначит ID
            false,    // no_local: true — не получать свои же сообщения (для pub/sub, обычно false)
            false,      // no_ack: true — автоподтверждение (всё, что получено, считается обработанным)
                              // no_ack: false RabbitMQ ждет подтверждения, что сообщение получено.
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
