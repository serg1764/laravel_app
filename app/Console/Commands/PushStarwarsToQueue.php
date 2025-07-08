<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessStarwarsRow;

class PushStarwarsToQueue extends Command
{
    protected $signature = 'starwars:push';
    protected $description = 'Отправляет строки из starwars в очередь RabbitMQ';

    public function handle()
    {
        $rows = DB::table('api_star_wars')->get();

        foreach ($rows as $row) {
            ProcessStarwarsRow::dispatch((array) $row);
            $this->info('Отправлено: ' . json_encode($row));
        }

        return Command::SUCCESS;
    }
}
