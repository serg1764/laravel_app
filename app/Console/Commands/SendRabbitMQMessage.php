<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendRabbitMQMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:send';

    /**
     * Отправляем тестовое сообщение в очередь.
     * Запускаем командой
     * php artisan rabbitmq:send
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
            $channel->queue_declare('hello', false, false, false, false);

            // Публикуем сообщение.
            $msg = new AMQPMessage('Hello World!');

            // кладем в созданную только что очередь
            $channel->basic_publish($msg, '', 'hello');

            // кладем в созданную уже до этого очередь
            $channel->basic_publish($msg, '', 'laravel');

            $this->info(" [x] Sent 'Hello World!'");

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            $this->error("RabbitMQ error: " . $e->getMessage());
        }
    }
}
