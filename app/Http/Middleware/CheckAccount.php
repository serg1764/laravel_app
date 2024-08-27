<?php

namespace App\Http\Middleware;

use App\Models\Helper;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check()) {
            $userId = auth()->id();

            // Получаем название роли пользователя
            $roleName = UserRole::getRoleNameByUserId($userId);

            // Проверяем, является ли роль user
            if (in_array($roleName, $roles)) {
                return $next($request);
            }
            elseif ($roleName === 'disabled'){
                return redirect('/disabled');
            }
        }
        else {
            return redirect('/login');
        }

        // Перенаправление, если пользователь не администратор
        return redirect('/');
    }
}
