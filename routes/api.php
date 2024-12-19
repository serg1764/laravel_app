<?php

use App\Models\ApiStarWars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Jobs\ProcessTextJob;
use App\Models\Helper;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/** перенести это в api.php и там пробовать
 * В папке Providers надо сделать
 * Тут наверное надо bootstrap/providers.php
 * Тут Надо bootstrap/app.php ?
 *
 * https://laravel.su/docs/11.x/routing#marsruty-api
 *
 * */
Route::post('/add-to-queue', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'text' => 'required|string|max:255',
    ]);

    // Отправляем задачу в очередь
    /*$res = ProcessTextJob::dispatch();
    Helper::logToDatabase('API', $res, 'первый апи и job');
    $res = ProcessTextJob::dispatch($validated['text'])->onQueue(env('REDIS_QUEUE', 'job_redis'));
    Helper::logToDatabase('API', $res, 'первый апи и job');*/

    /** По умолчанию default из config/queue.php*/
    ProcessTextJob::dispatch($validated['text']);
    ProcessTextJob::dispatch('sdf');
    ProcessTextJob::dispatch($validated['text'])->onQueue(env('REDIS_QUEUE', 'job_redis'));
    ProcessTextJob::dispatch('sdf')->onQueue(env('REDIS_QUEUE', 'job_redis'));

    /** А тут явно redis прописываем из config/queue.php
     *
     * Отсюда api забирать https://dog.ceo/dog-api/breeds-list#google_vignette
     *
     * */
    ProcessTextJob::dispatch($validated['text'])
        ->onConnection('redis') // Указываем соединение
        ->onQueue(env('REDIS_QUEUE', 'job_redis')); // Указываем имя очереди

    ProcessTextJob::dispatch('sdf')
        ->onConnection('redis')
        ->onQueue(env('REDIS_QUEUE', 'job_redis'));


    return response()->json(['status' => 'Task added to queue!!!'], 200);
});


// Новый маршрут для вызова метода fetchFromApi
Route::post('/star-wars-fetch', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'text' => 'required|string|max:255',
    ]);
    $apiStarWars = new ApiStarWars();
    $result = $apiStarWars->fetchFromApi();

    if($result['success']){
        ProcessTextJob::dispatch($result['data'])
            ->onConnection('redis')
            ->onQueue(env('REDIS_QUEUE_API', 'job_redis_api'));
    }

    return response()->json($result, 200);
});
