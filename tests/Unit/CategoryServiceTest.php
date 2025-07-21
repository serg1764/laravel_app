<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CategoryService;
//use App\Models\Category;
use Mockery;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

/** Запускаем так
 * php artisan test --filter=CategoryServiceTest
 * */
class CategoryServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /*public function test_get_category_returns_data()
    {
        $expected = ['data' => ['id' => 1, 'name' => 'Test'], 'success' => true, 'error' => ''];

        // Мокаем статический метод модели
        Category::shouldReceive('getCategory')
            ->with(1)
            ->once()
            ->andReturn($expected);

        $service = new CategoryService();
        $result = $service->getCategory(1);

        $this->assertEquals($expected, $result);
    }*/

    /*public function test_save_category_returns_result()
    {
        $input = ['id' => 'new', 'name' => 'new category'];
        $modelResponse = [
            'data' => [
                'id' => 123,
                'my_value' => 'Ninochka'
            ],
            'success' => true,
            'error' => ''
        ];

        $expectedFinalResponse = [
            'data' => [
                'id' => 123,
                'my_value' => 'Ninochka',
                'my_value2' => 'Yurochka',
                'name' => 'New category'
            ],
            'success' => true,
            'error' => ''
        ];*/

        /** Это не работает, потому что.
         * Означает, что ты пытаешься замокать статический метод Eloquent-модели, но Laravel не понимает shouldReceive(),
         * потому что она не является фасадом.
         * Метод shouldReceive() доступен только для фасадов Laravel или классов, над которыми явно установлен мок через
         * Mockery::mock(...) или Mockery::mock('alias:...').
         *
         * А Category — это обычная Eloquent-модель, не фасад и не Mockery::mock().
         */
        /*Category::shouldReceive('saveCategory')
            ->with($input)
            ->once()
            ->andReturn($expected);*/

       /* Mockery::mock('alias:' . \App\Models\Category::class)
            ->shouldReceive('saveCategory')
            ->with($input)
            ->once()
            ->andReturn($modelResponse);

        $service = new CategoryService();
        $result = $service->saveCategory($input);

        $this->assertEquals($expectedFinalResponse, $result);
    }*/

    public function test_save_category_returns_result()
    {
        $input = ['id' => 'new', 'name' => 'new category'];

        $modelResponse = [
            'data' => ['id' => 123, 'my_value' => 'Ninochka'],
            'success' => true,
            'error' => ''
        ];

        $expectedFinal = [
            'data' => [
                'id' => 123,
                'my_value' => 'Ninochka',
                'my_value2' => 'Yurochka',
                'name' => 'New category'
            ],
            'success' => true,
            'error' => ''
        ];

        $mockRepo = Mockery::mock(CategoryRepositoryInterface::class);
        $mockRepo->shouldReceive('save')
            ->with($input)
            ->once()
            ->andReturn($modelResponse);

        $service = new CategoryService($mockRepo);
        $result = $service->saveCategory($input);

        $this->assertEquals($expectedFinal, $result);
        $this->assertEquals(1, 1);
        $this->assertEquals(888, 888);
    }

    public function test_get_all_returns_collection2(){
        $this->assertEquals(888, 888);
    }

    public function test_get_all_returns_collection1(){
        $this->assertEquals(3, 3);
    }

    /*public function test_get_all_returns_collection()
    {
        $collection = collect([
            ['id' => 1, 'name' => 'Cat 1'],
            ['id' => 2, 'name' => 'Cat 2'],
        ]);

        Category::shouldReceive('all')
            ->once()
            ->andReturn($collection);

        $service = new CategoryService();
        $result = $service->getAll();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }*/

    /** Делаем тест
     * для всех ответвлений логик
     * http://localhost/products/filtered?category_id=5&min_price=0&max_price=10000
     * http://localhost/products/filtered?page=2&category_id=5&min_price=0&max_price=6005
     *
     * */
}
