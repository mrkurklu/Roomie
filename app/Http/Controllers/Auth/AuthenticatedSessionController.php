<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user()->load('roles');
        
        // Tarayıcı dilini al ve kullanıcının language field'ını güncelle (eğer boşsa veya farklıysa)
        $browserLanguage = $request->input('browser_language', 'tr');
        
        // Desteklenen dilleri kontrol et
        $supportedLanguages = ['tr', 'en', 'de', 'fr', 'es', 'it', 'ru', 'ar', 'zh', 'ja'];
        if (in_array($browserLanguage, $supportedLanguages)) {
            // Kullanıcının language field'ı boşsa veya farklıysa güncelle
            if (empty($user->language) || $user->language !== $browserLanguage) {
                $user->language = $browserLanguage;
                $user->save();
            }
        }
        
        // Role göre yönlendirme
        if ($user->hasRole('superadmin') || $user->hasRole('müdür') || $user->hasRole('manager')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('personel') || $user->hasRole('staff')) {
            return redirect()->route('staff.tasks');
        } elseif ($user->hasRole('misafir') || $user->hasRole('guest')) {
            return redirect()->route('guest.welcome');
        }

        // Varsayılan olarak admin dashboard'a yönlendir
        return redirect()->route('admin.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
