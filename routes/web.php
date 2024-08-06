<?php

use App\Http\Middleware\CheckStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use \App\Http\Controllers\Blog\Admin\MainController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});


\App\Models\Helpers\MyHelper::get_pr('идем в админ');
// Применение middleware только к этому маршруту
Route::get('/admin', [MainController::class, 'index'])
    ->middleware(CheckStatus::class)
    ->name('admin.index');

//Auth::routes();


// Маршрут для отображения формы
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Маршрут для обработки данных формы
Route::post('/login', function (Request $request) {
    // Валидация входных данных
    $validated = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $data = $request->all();
    \App\Models\Helpers\MyHelper::get_pr('$data');
    \App\Models\Helpers\MyHelper::get_pr($data);
    \App\Models\Helpers\MyHelper::get_pr($validated);

    // Попытка аутентификации
    if (Auth::attempt($validated, $request->filled('remember'))) {

        \App\Models\Helpers\MyHelper::get_pr('получилось' );
        // Аутентификация успешна
        $request->session()->regenerate();
        return redirect()->intended('/admin'); // Перенаправление после успешного входа
    }

    \App\Models\Helpers\MyHelper::get_pr('не получилось' );
    // Аутентификация не удалась
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});


\App\Models\Helpers\MyHelper::get_pr('nen');

$user = Auth::user();
\App\Models\Helpers\MyHelper::get_pr('$user');
\App\Models\Helpers\MyHelper::get_pr($user);

// В контроллере или любом другом месте
if (Auth::check()) {

    \App\Models\Helpers\MyHelper::get_pr($user);
} else {
    \App\Models\Helpers\MyHelper::get_pr('Не залогинен');
}


/*Route::get('/admin/settings', [AdminController::class, 'settings'])
    ->middleware(CheckStatus::class)
    ->name('admin.settings');*/


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/** Admin side */

/*Route::group(['middleware' => ['status', 'auth']], function () {
    $groupData = [
        'namespace' => 'Blog\Admin',
        'prefix' => 'admin',
    ];

    Route::group($groupData, function () {
        Route::resource('index', 'MainController')
            ->names ('blog.admin.index');

        });
});*/
