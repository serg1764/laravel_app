<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminCategoryPageTest extends TestCase
{
    use WithFaker;

    /** запускаем командой php artisan test tests/Feature/AdminCategoryPageTest.php*/
    public function test_admin_category_page_returns_success_for_admin(): void
    {
        // Получаем пользователя с именем admin
        $user = User::where('name', 'admin')->firstOrFail();

        // Убираем middleware, если оно мешает в тестовой среде
        $this->withoutMiddleware([
            \App\Http\Middleware\CheckAdmin::class,
        ]);

        // Выполняем запрос от имени admin
        $response = $this
            ->actingAs($user)
            ->get('/admin/category/5');

        $response->assertStatus(200);
        $response->assertSee('Кнопочные'); // Проверяем наличие текста на странице
    }

    public function test_admin_category_page_redirects_guest_to_login(): void
    {
        auth()->logout();
        session()->flush();

        $response = $this->get('/admin/category/5');

        $response->assertRedirect('/login');
    }

    public function test_admin_category_page_redirects_non_admin_user(): void
    {
        // Получаем пользователя с именем SERGEI
        $user = User::where('name', 'SERGEI')->firstOrFail();

        $response = $this->actingAs($user)->get('/admin/category/5');

        // Предположим, у него роль не admin — тогда он будет редиректнут на /account
        $response->assertRedirect('/account');
    }

    public function test_admin_can_create_new_category(): void
    {
        // Подключаем админа из базы
        $admin = User::where('name', 'admin')->firstOrFail();

        // Авторизация
        $this->actingAs($admin);

        // Данные для создания новой категории
        $input = [
            'id' => 'new',
            'parent_id' => 1,
            'title' => 'Тестовая категория',
            'name' => 'test_category',
            'description' => 'Описание новой категории',
            'url' => 'test-category',
            'content' => '<p>Контент</p>',
            'imgs' => 'img_test.jpg',
            'inactive' => false,
        ];

        $input['name'] = ucfirst($input['name']);

        // Отправка запроса
        $response = $this->post('/admin/save-category', $input);

        // Проверка успешного ответа и структуры JSON
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $input['name'],
                    'url' => $input['url'],
                ]
            ]);

        // Проверка, что категория записалась в БД
        $this->assertDatabaseHas('categories', [
            'name' => $input['name'],
            'title' => $input['title'],
            'url' => $input['url'],
            'parent_id' => $input['parent_id'],
            'description' => $input['description'],
            'content' => $input['content'],
            'imgs' => $input['imgs'],
            'inactive' => $input['inactive'],
        ]);
    }
}
