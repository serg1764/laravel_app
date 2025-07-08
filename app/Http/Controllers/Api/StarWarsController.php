<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTextJob;
use App\Models\ApiStarWars;
use Illuminate\Http\JsonResponse;

class StarWarsController extends Controller
{
    public function fetch(): JsonResponse
    {
        $apiStarWars = new ApiStarWars();
        $result = $apiStarWars->fetchFromApi();

        if ($result['success']) {
            /**
             * Отправляем полученные данные в очередь:
             * - Используем соединение redis
             * - Имя очереди задаём через переменную окружения REDIS_QUEUE_API
             */
            ProcessTextJob::dispatch($result['data'])
                ->onConnection('redis')
                ->onQueue(env('REDIS_QUEUE_API', 'job_redis_api'));
        }

        return response()->json($result, 200);
    }
}
