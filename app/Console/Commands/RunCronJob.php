<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTextJob;
use App\Models\ApiStarWars;
use App\Models\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RunCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-cron-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Параметры запроса для POST
        /*$data = [
            'text' => 'example string', // Вставьте нужное значение
        ];

        // Отправка POST запроса на ваш маршрут API
        $response = Http::post(url('/star-wars-fetch'), $data);

        // Обработка ответа
        if ($response->successful()) {
            $this->info('API запрос успешно выполнен.');
        } else {
            $this->error('Произошла ошибка при выполнении API запроса: ' . $response->status());
        }*/

        $apiStarWars = new ApiStarWars();
        $result = $apiStarWars->fetchFromApi();
        //print_r ($result);

        if($result['success']){
            ProcessTextJob::dispatch($result['data'])
                ->onConnection('redis')
                ->onQueue(env('REDIS_QUEUE_API', 'job_redis_api'));
        }
        else{
            Helper::logToDatabase('API', $result, $result['error']);
        }

        print_r ("OK - " . $result['success'] . PHP_EOL);
        //return response()->json($result, 200);
        //return 1;

    }
}
