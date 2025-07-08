<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToQueueRequest;
use App\Jobs\ProcessTextJob;
use App\Models\Helper;
use Illuminate\Http\JsonResponse;

class QueueController extends Controller
{
    public function testStore(AddToQueueRequest $request): JsonResponse
    {
        $text = $request->validated()['text'];

        /**
         * 1. Стандартная постановка задачи в очередь.
         * Используется очередь по умолчанию, указанная в config/queue.php → default.
         */
        ProcessTextJob::dispatch($text);

        /**
         * 2. То же самое, но с жёстко заданным текстом (для отладки).
         */
        ProcessTextJob::dispatch('sdf');

        /**
         * 3. Постановка задачи в именованную очередь.
         * Очередь задаётся через .env → REDIS_QUEUE=job_redis (или значение по умолчанию 'job_redis').
         */
        ProcessTextJob::dispatch($text)->onQueue(env('REDIS_QUEUE', 'job_redis'));

        /**
         * 4. То же самое, но с жёстко заданным текстом.
         */
        ProcessTextJob::dispatch('sdf')->onQueue(env('REDIS_QUEUE', 'job_redis'));

        /**
         * 5. Указано и соединение, и имя очереди.
         * Явно указывается, что задача идёт в соединение redis (см. config/queue.php → connections.redis),
         * и помещается в очередь job_redis.
         */
        ProcessTextJob::dispatch($text)
            ->onConnection('redis')
            ->onQueue(env('REDIS_QUEUE', 'job_redis'));

        /**
         * 6. Тоже с жёстким текстом, но указано и соединение, и очередь.
         * Полезно для отладки и ручных запусков.
         */
        ProcessTextJob::dispatch('sdf')
            ->onConnection('redis')
            ->onQueue(env('REDIS_QUEUE', 'job_redis'));

        // Можно логировать, если нужно
        Helper::logToDatabase('API', $text, 'Добавлено в очередь через контроллер');

        return response()->json(['status' => 'Task added to queue!!!'], 200);
    }
}
