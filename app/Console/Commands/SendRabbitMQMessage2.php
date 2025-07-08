<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendRabbitMQMessage2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:send2 {message=Hello World!.}';

    /**
     * Отправляем тестовое сообщение в очередь.
     * Запускаем командой
     * php artisan rabbitmq:send2 HelloWorld......
     *
     * @var string
     */
    protected $description = 'Отправляем тестовое сообщение в очередь.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Connecting to RabbitMQ...");

            // создаем коннект и канал
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

            // получаем параметр и командной строки
            $message = $this->argument('message');

            // Публикуем сообщение.
            $msg = new AMQPMessage($message);

            $msg = new AMQPMessage(
                $message, // 🔹 1. Тело сообщения — строка, которую ты отправляешь в очередь.
                [
                    // 🔹 2. Опции сообщения:
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                    // 🔸 delivery_mode:
                    // - DELIVERY_MODE_NON_PERSISTENT (1): сообщение теряется при перезапуске RabbitMQ.
                    // - DELIVERY_MODE_PERSISTENT (2): сообщение сохраняется на диск и выживает перезапуск брокера
                    // (если очередь тоже durable).
                ]
            );

            // кладем в созданную только что очередь
            $channel->basic_publish($msg, '', 'task_queue');

            $this->info(" [x] Sent '$message'");

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            $this->error("RabbitMQ error: " . $e->getMessage());
        }
    }
}
