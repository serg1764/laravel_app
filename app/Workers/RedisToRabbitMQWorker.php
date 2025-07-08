<?php

namespace App\Workers;

use React\EventLoop\Loop;
use Illuminate\Support\Facades\Redis;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RedisToRabbitMQWorker
{
    protected $loop;
    protected $redis;
    protected $queueName;
    protected $amqp;
    protected $channel;

    /**
     * @var AMQPStreamConnection $this->amqp
     *
     * @throws \Exception
     */
    public function __construct()
    {
        // Создаем цикл событий
        $this->loop = Loop::get();

        // Подключение к Redis для очередей
        $this->redis = Redis::connection('queues');

        // Имя очереди
        $this->queueName = env('REDIS_QUEUE_API');

        // Создаем подключение к RabbitMQ
        try {
            $this->amqp = new AMQPStreamConnection(
                \Config::get('rabbitmq.host'),
                \Config::get('rabbitmq.port'),
                \Config::get('rabbitmq.user'),
                \Config::get('rabbitmq.password')
            );
            $this->channel = $this->amqp->channel();
        } catch (\Exception $e) {
            echo "Ошибка при подключении к RabbitMQ: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    public function run(): void
    {
        try {
            // Подключаемся к RabbitMQ и создаем канал
            $this->channel->queue_declare('redis_to_rabbit', false, true, false, false);

            // Запускаем процессы
            $this->startProcess(5);  // Процесс с паузой 5 секунд
            $this->startProcess(7); // Процесс с паузой 10 секунд

            // Запускаем цикл событий
            $this->loop->run();
        } catch (\Exception $e) {
            echo "Ошибка при запуске работы: " . $e->getMessage() . "\n";
        } finally {
            // Закрываем каналы и соединения после завершения всех операций
            try {
                $this->channel->close();
            } catch (\Exception $e) {
                echo "Ошибка при закрытии канала: " . $e->getMessage() . "\n";
            }

            try {
                $this->amqp->close();
            } catch (\Exception $e) {
                echo "Ошибка при закрытии соединения с RabbitMQ: " . $e->getMessage() . "\n";
            }
        }
    }

    // Функция для запуска процесса с заданной паузой
    protected function startProcess($interval): void
    {
        echo "Запускаем процесс: " . $interval . " сек \n";
        $this->loop->addPeriodicTimer($interval, function() use ($interval){
            $this->processQueue($interval);
        });
    }

    // Обработка заявок из очереди Redis
    protected function processQueue($interval): void
    {
        Делаем 3 базу для редиса и туда кладем значения.

        // Забрать и удалить первый элемент из очереди
        $firstElement = $this->redis->lpop('myQueue');
        //$firstElement = $this->redis->lpop('laravel_database_queues:');

        /*$range = Redis::connection('queues')->command('LRANGE', ['laravel_database_myQueue', 1, 10]);
        // Выводим полученные элементы
        print_r($range);
        print_r("\n");
        print_r($this->redis);
        print_r("\n");

        $count = Redis::connection('queues')->llen('laravel_database_queues:job_redis_api');
        $keys = Redis::connection('queues')->keys('*');
        print_r($count);
        print_r("\n");
        print_r($keys);
        print_r("\n");

        //firstElement = $this->redis->lpop($keys[1]);


        $redis = Redis::connection('queues')->getEventDispatcher();
        print_r("\n");
        print_r($redis);*/


        Redis::connection('queues')->rpush('myQueue', 'myJob');


        print_r("\n");
        print_r($firstElement);

        $type = Redis::connection('queues')->type('laravel_database_queues:job_redis_api');
        // Выводим тип
        echo "Тип данных ключа: $type";// Проверяем, существует ли ключ
        $exists = Redis::connection('queues')->exists('laravel_database_queues:job_redis_api');

        if ($exists) {
            echo "Ключ существует.";
        } else {
            echo "Ключ не существует.";
        }
        $keys = Redis::connection('queues')->keys('laravel_database_queues:*');

        print_r($keys);


        $keys = Redis::connection('queues')->keys('*');

        print_r($keys);

        $typeNotify = Redis::connection('queues')->type('laravel_database_queues:job_redis_api:notify');
        echo "Тип данных ключа 'laravel_database_queues:job_redis_api:notify': $typeNotify";

        $data = Redis::connection('queues')->hgetall('laravel_database_queues:job_redis_api');
        print_r($data);

        if ($firstElement === false) {
            echo "Очередь пуста или нет элементов.\n";
            return;
        }

        /*print_r($this->queueName . "\n");
        print_r($this->redis);
        print_r("\n");

        $keys = $this->redis->keys('*');
        print_r($keys);
        print_r("\n");*/

        // Добавляем задачу в RabbitMQ
        echo "Продолжаем процесс: " . $interval . " сек \n";
        echo "Task adding to RabbitMQ: " . $firstElement . "\n";
        print_r($firstElement);
        $msg = new AMQPMessage($firstElement);
        $this->channel->basic_publish($msg, 'redis_to_rabbit');
        echo " [x] Task adding to RabbitMQ\n";
    }
}
