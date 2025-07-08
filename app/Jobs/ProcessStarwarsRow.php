<?php

namespace App\Jobs;

use App\Models\StarWarsRebbit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStarwarsRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function handle(): void
    {
        Log::info('Обработка строки starwars', $this->data);
        sleep(2);
        Log::info($this->data['name']);

        /** Пример: сохранить в другую таблицу, если нужно
         * Кладём через Фасад. Поля с датами created_at и updated_at автоматически не заполняются.*/
        \DB::table('api_star_wars_rebbit')->insert(['name' => $this->data['name']]);

        /** Теперь кладем через модель.
         * Поля с датами created_at и updated_at автоматически заполняются.*/

        StarWarsRebbit::create(['name' => $this->data['name']]);
    }
}
