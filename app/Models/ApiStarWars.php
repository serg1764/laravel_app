<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class ApiStarWars extends Model
{
    use HasFactory;

    // Имя таблицы
    protected $table = 'api_star_wars';

    // Заполняемые поля
    protected $fillable = [
        'id',
        'iid',
        'name',
        'url',
        'json',
    ];

    // Текущий ID
    protected int $currentId;

    // Константа для API URL
    public const API_URL = 'https://swapi.py4e.com/api/';

    // Статическое свойство для хранения экземпляра модели
    protected static ?ApiStarWars $instance = null;

    protected const MAX_API_ID = 87;

    // Конструктор
    public function __construct(array $attributes = [])
    {
        // Проверка, если экземпляр модели уже существует
        if (self::$instance) {
            // Используем уже существующий экземпляр
            return self::$instance;
        }

        parent::__construct($attributes);

        // Инициализируем экземпляр модели
        self::$instance = $this;

        // Получаем последний iid, отсортированный по id
        $lastRecord = self::orderBy('id', 'desc')->first();

        // Инициализируем currentId
        $this->currentId = $lastRecord ? $lastRecord->iid : 0;
    }

    // Метод для получения текущего ID
    public function getCurrentId():int
    {
        return $this->currentId;
    }

    // Метод для установки текущего ID
    public function setCurrentId($nextId):void
    {
        $this->currentId = $nextId;
    }

    // Метод для получения следующего ID
    public function getNextId():int
    {
        $nextId = $this->getCurrentId() + 1;

        if($nextId === self::MAX_API_ID) {
            $nextId =1;
        };

        return $nextId;
    }


    // Метод для обращения к API
    public function fetchFromApi():array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        try {
            $nextId = $this->getNextId();
            $apiUrl = self::API_URL . 'people/' . $nextId;

            // Отправка HTTP-запроса с таймаутом
            $response = Http::timeout(5)->get($apiUrl);

            // Проверка успешного статуса
            if ($response->successful()) {
                $Result['data'] = json_decode($response->body(), true);
                $Result['success'] = true;
            }
            else
            {
                if ($response->status() === 404) {
                    Helper::logToDatabase('API', $nextId, 'Увеличиваем $nextId - был' . $this->currentId);
                    $this->setCurrentId($nextId);
                    return $this->fetchFromApi();
                }
                $Result['error'] = 'API Error: ' . $response->status() . ' - ' . $response->body();
            }
        }
        catch (ConnectionException $exception) {
            // Обработка ошибок подключения
            $Result['error'] = 'Connection Error: Unable to connect to the API. ' . $exception->getMessage();
        } catch (\Exception $exception) {
            // Обработка всех прочих ошибок
            $Result['error'] = 'Unexpected Error: ' . $exception->getMessage();
        }

        return $Result;
    }

    // Метод для получения данных из API и сохранения в таблицу
    public static function fetchAndStore($Data):array
    {
        $Result = [
            'data' => [],
            'success' => false,
            'error' => ''
        ];

        //Helper::logToDatabase('API', $Data, '$Data');

        try {
            // Данные приходят в параметр $Data
            if (empty($Data)) {
                $Result['error'] = 'Invalid data provided: Missing required id.';
                return $Result;
            }

            preg_match('/([^\/]+)\/$/', $Data['url'], $matches);
            //Helper::logToDatabase('API', $matches[1], '$matches[1]');

            // Сохраняем полученные данные в таблицу
            self::create([
                'iid' => $matches[1],
                'name' => $Data['name'] ?? 'Unknown',
                'url' => self::API_URL . 'people/' . $matches[1] . '/',
                'json' => json_encode($Data),
            ]);

            $Result['success'] = true;
        }
        catch (\Exception $exception) {
            $Result['error'] = 'Unexpected Error: ' . $exception->getMessage();
            Helper::logToDatabase('API', $Result['error'], '$Result[error]');
        }

        //Helper::logToDatabase('API', $Result['error'], 'Прошло успешно?');
        return $Result;
    }

}
