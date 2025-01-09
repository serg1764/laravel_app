<?php

namespace App\Console\Commands;

use App\Models\ApiStarWars;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublishRebbit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rebbitmq:publish';

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

        $channel->exchange_declare('laravel','fanout', false, true, false);
        $channel->queue_declare('laravel', false, true, false, false);

        $channel->queue_bind('laravel', 'laravel');

        $apiStarWars = new ApiStarWars();
        $result = $apiStarWars->fetchFromApi();
        $data = json_encode($result);

        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, 'laravel');

        echo " [x] Sent 'Hello World!'\n";

        $channel->close();
        $connection->close();
    }
}
