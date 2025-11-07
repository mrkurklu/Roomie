<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\Response)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Önce session'dan kontrol et (en güncel)
        $locale = session('locale');
        
        // Session'da yoksa kullanıcıdan al
        if (!$locale && Auth::check()) {
            $user = Auth::user();
            $locale = $user->language ?? config('app.locale', 'tr');
            // Session'a kaydet (sonraki istekler için)
            session(['locale' => $locale]);
        }
        
        // Hala yoksa default locale kullan
        if (!$locale) {
            $locale = config('app.locale', 'tr');
        }

        // Locale'i ayarla
        App::setLocale($locale);

        return $next($request);
    }
}

