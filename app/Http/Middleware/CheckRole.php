<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Gelen isteği handle et.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles // Birden fazla rol parametresi alabilir
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) { // Kullanıcı giriş yapmamışsa
            return redirect('login');
        }

        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request); // Rol eşleşirse, isteğe devam et
            }
        }

        abort(403, 'Unauthorized action.'); // Hiçbir rol eşleşmezse, 403 Hatası ver
    }
}
