<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // проверяем авторизован ли пользователь
        if (!Auth::check()) {
            // если не авторизован, перенаправляем на страницу входа
            return redirect()->route('login')->withErrors([
                'error' => 'Для доступа к этой странице необходимо авторизоваться.'
            ]);
         }

        // проверяем роль пользователя
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('main.index')->withErrors([
                'error' => 'У вас недостаточно прав для доступа к этой странице.'
            ]);
        }

        // Пользователь админ - пропускаем дальше
        return $next($request);
    }
}
