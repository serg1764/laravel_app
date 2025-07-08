<?php

namespace App\Console\Commands;

use App\Jobs\ProcessStarwarsRow1;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PushStarwarsToQueue1 extends Command
{
    protected $signature = 'starwars12:push';
    protected $description = 'Отправляет строки из starwars в очередь RabbitMQ с заголовком source=laravel1';

    public function handle()
    {
        \Log::info('Команда Starwars1Push реально запущена');
        $rows = DB::table('api_star_wars')
            ->where('name', 'ilike', '%w%')
            ->get();

        foreach ($rows as $row) {
            // Преобразуем stdClass в массив
            $payload = (array)$row;

            ProcessStarwarsRow1::dispatch($payload);
            //ProcessStarwarsRow1::dispatch($payload)->onQueue('queue_laravel1');

            $this->info('Отправлено в очередь: ' . json_encode($payload));
        }

        return Command::SUCCESS;
    }
}
