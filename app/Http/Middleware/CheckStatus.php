<?php

namespace App\Http\Middleware;

use App\Models\Helpers\MyHelper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        MyHelper::get_pr(__CLASS__);
        MyHelper::get_pr(Auth::user());
        MyHelper::get_pr(!Auth::check());
        //MyHelper::get_pr((new User((array)Auth::user()))->isAdministrator());
        //return $next($request);
        /*if(Auth::user() && (new User((array)Auth::user()))->isAdministrator()){
            return $next($request);
        }
        else{
            return redirect('/');
        }*/

        $currentUrl = $request->url();
        $currentPath = $request->path();
        MyHelper::get_pr($currentUrl);
        MyHelper::get_pr($currentPath);
        MyHelper::get_pr('(new \App\Models\User)->isAdministrator()');
        //MyHelper::get_pr((new \App\Models\User));
        MyHelper::get_pr((new \App\Models\User(['admin']))->isAdministrator());
        MyHelper::get_pr('Мы в чекСтатусе');

        sleep(5);

        // Если пользователь авторизован, но не администратор, перенаправляем на главную страницу
        if (Auth::check() && (new \App\Models\User)->isAdministrator()) {
            return $next($request);
        }
        else{
            //return $next($request);
            return redirect('/');
        }

    }
}
