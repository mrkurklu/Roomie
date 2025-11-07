@extends('layouts.portal')

@section('title', 'Hesap Ayarları')

@section('sidebar')
    @if(($role ?? 'Yönetim') === 'Yönetim')
        @include('admin.partials.sidebar', ['active' => 'settings'])
    @elseif(($role ?? 'Yönetim') === 'Personel')
        @include('staff.partials.sidebar', ['active' => 'settings'])
    @else
        @include('guest.partials.sidebar', ['active' => 'settings'])
    @endif
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">{{ __('settings') }}</h1>
        <p class="text-sm text-muted-foreground mt-1">{{ __('profile_information') }}</p>
    </div>

    <!-- Profile Information -->
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header>
            <x-ui.card-title>{{ __('profile_information') }}</x-ui.card-title>
            <p class="text-sm text-muted-foreground mt-1">{{ __('profile_information') }}</p>
        </x-ui.card-header>
        <x-ui.card-content>
            <form id="language-form" method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium">{{ __('name') }}</label>
                    <x-ui.input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium">{{ __('email') }}</label>
                    <x-ui.input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                    @error('email')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="language" class="text-sm font-medium">{{ __('language_preference') }}</label>
                    <select id="language" name="language" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" onchange="document.getElementById('language-form').submit();">
                        @foreach(\App\Services\TranslationService::getSupportedLanguages() as $code => $name)
                            <option value="{{ $code }}" {{ old('language', $user->language ?? 'tr') === $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-muted-foreground mt-1">{{ __('language_description') }}</p>
                    @error('language')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-muted-foreground">
                                E-posta adresiniz doğrulanmamış.
                                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="underline text-sm text-primary hover:text-primary/80">
                                        Doğrulama e-postasını tekrar gönder
                                    </button>
                                </form>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm text-green-600 dark:text-green-400">
                                    Yeni bir doğrulama bağlantısı e-posta adresinize gönderildi.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <x-ui.button type="submit">{{ __('save') }}</x-ui.button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-muted-foreground"
                        >{{ __('saved_successfully') }}</p>
                    @endif
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Update Password -->
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-header>
            <x-ui.card-title>{{ __('update_password') }}</x-ui.card-title>
            <p class="text-sm text-muted-foreground mt-1">{{ __('update_password') }}</p>
        </x-ui.card-header>
        <x-ui.card-content>
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="space-y-2">
                    <label for="current_password" class="text-sm font-medium">{{ __('current_password') }}</label>
                    <x-ui.input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                    @error('current_password', 'updatePassword')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium">{{ __('new_password') }}</label>
                    <x-ui.input id="password" name="password" type="password" autocomplete="new-password" />
                    @error('password', 'updatePassword')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-medium">{{ __('confirm_password') }}</label>
                    <x-ui.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-sm text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4">
                    <x-ui.button type="submit">{{ __('save') }}</x-ui.button>

                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-muted-foreground"
                        >{{ __('saved_successfully') }}</p>
                    @endif
                </div>
            </form>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Delete Account -->
    <x-ui.card class="border-none shadow-sm border-destructive/20">
        <x-ui.card-header>
            <x-ui.card-title class="text-destructive">{{ __('delete_account') }}</x-ui.card-title>
            <p class="text-sm text-muted-foreground mt-1">{{ __('delete_account') }}</p>
        </x-ui.card-header>
        <x-ui.card-content>
            <div class="space-y-4">
                <p class="text-sm text-muted-foreground">
                    Hesabınız silindiğinde, tüm kaynaklarınız ve verileriniz kalıcı olarak silinecektir. 
                    Hesabınızı silmeden önce, saklamak istediğiniz herhangi bir veri veya bilgiyi indirin.
                </p>

                <x-ui.button 
                    variant="destructive"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                >
                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                    Hesabı Sil
                </x-ui.button>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<!-- Delete Account Modal -->
<div 
    x-data="{ show: @js($errors->userDeletion->isNotEmpty()) }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') show = true"
    x-on:close-modal.window="show = false"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    style="display: none;"
>
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.away="show = false"
        class="bg-popover border border-border rounded-lg shadow-lg p-6 max-w-md w-full mx-4"
    >
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-foreground">
                Hesabınızı silmek istediğinizden emin misiniz?
            </h2>

            <p class="text-sm text-muted-foreground">
                Hesabınız silindiğinde, tüm kaynaklarınız ve verileriniz kalıcı olarak silinecektir. 
                Hesabınızı kalıcı olarak silmek istediğinizi onaylamak için şifrenizi girin.
            </p>

            <div class="space-y-2">
                <label for="password" class="text-sm font-medium">Şifre</label>
                <x-ui.input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Şifrenizi girin"
                />
                @error('password', 'userDeletion')
                    <p class="text-sm text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <x-ui.button 
                    type="button"
                    variant="outline"
                    x-on:click="show = false"
                >
                    İptal
                </x-ui.button>

                <x-ui.button 
                    type="submit"
                    variant="destructive"
                >
                    Hesabı Sil
                </x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
