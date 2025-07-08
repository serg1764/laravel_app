<?php

namespace App\Jobs;

use App\Models\StarWarsRebbit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStarwarsRow1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
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

    /**
     * Указываем exchange и очередь прямо здесь.
     */
    public function viaConnection()
    {
        return 'rabbitmq';
    }

    public function viaQueue()
    {
        Log::info('Вызван Job из очереди: withMetadata ' . $this->job->getQueue());
        return 'queue_laravel1'; // Название очереди, как в RabbitMQ
    }


    /**
     * Добавляем заголовки RabbitMQ (headers).
     * Очередь забиндили и по заголовку будем в эту очередь класть
     * exchange, если не хотим пользоваться по умолчанию.
     */
    public function withMetadata(): array
    {
        Log::info('Вызван Job из очереди: withMetadata ' . $this->job->getQueue());
        return [
            'exchange' => 'input_headers_exchange_name1', // Название нужного exchange
            'headers' => [
                'source' => ['S', 'laravel1'],
            ],
        ];
    }
}
