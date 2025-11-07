<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('roles');
        
        // Kullanıcının rolünü belirle
        $role = 'Yönetim';
        if ($user->hasRole('personel')) {
            $role = 'Personel';
        } elseif ($user->hasRole('misafir')) {
            $role = 'Misafir';
        }
        
        return view('profile.edit', [
            'user' => $user,
            'role' => $role,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Mevcut değerleri sakla (değişiklik kontrolü için)
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldLanguage = $user->language;
        
        // Form verilerini doldur
        $user->fill($request->validated());
        
        // Dil ayarını güncelle
        if ($request->has('language')) {
            $newLocale = $request->input('language');
            $user->language = $newLocale;
            // Session'a kaydet (middleware için)
            session(['locale' => $newLocale]);
            // Locale'i hemen güncelle (bu sayfa için)
            App::setLocale($newLocale);
        }

        // Gerçek değişiklikleri kontrol et
        $nameChanged = $oldName !== $user->name;
        $emailChanged = $oldEmail !== $user->email;
        $languageChanged = $oldLanguage !== $user->language;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Eğer sadece dil değiştiyse, dashboard'a yönlendir (ayarlar sayfasını kapat)
        if ($languageChanged && !$nameChanged && !$emailChanged) {
            // Kullanıcının rolüne göre dashboard'a yönlendir
            if ($user->hasRole('superadmin') || $user->hasRole('müdür')) {
                return Redirect::route('admin.dashboard')->with('status', 'profile-updated');
            } elseif ($user->hasRole('personel')) {
                return Redirect::route('staff.tasks')->with('status', 'profile-updated');
            } elseif ($user->hasRole('misafir')) {
                return Redirect::route('guest.welcome')->with('status', 'profile-updated');
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
