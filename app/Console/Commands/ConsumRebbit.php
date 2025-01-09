<?php

namespace App\Console\Commands;

use App\Models\ApiStarWars;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumRebbit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rebbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            \Config::get('rabbitmq.host'),
            \Config::get('rabbitmq.port'),
            \Config::get('rabbitmq.user'),
            \Config::get('rabbitmq.password'));
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', "\n";
            $data = json_decode($msg->body, true);
            print_r($data['data']);
            ApiStarWars::fetchAndStore($data['data']);
        };

        $channel->basic_consume('laravel', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
