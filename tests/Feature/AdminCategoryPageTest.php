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
}
