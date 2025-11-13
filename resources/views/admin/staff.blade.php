@extends('layouts.portal')

@section('title', 'Personeller - Yönetim Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Personel ekleme modal açma
        document.getElementById('create_staff_btn')?.addEventListener('click', function() {
            document.getElementById('create_modal').classList.remove('hidden');
        });

        // Personel düzenleme modal açma
        document.querySelectorAll('[data-edit-staff]').forEach(btn => {
            btn.addEventListener('click', function() {
                const staffId = this.getAttribute('data-staff-id');
                const staffName = this.getAttribute('data-staff-name');
                const staffEmail = this.getAttribute('data-staff-email');
                const staffLanguage = this.getAttribute('data-staff-language') || 'tr';
                
                document.getElementById('edit_staff_id').value = staffId;
                document.getElementById('edit_staff_name').value = staffName;
                document.getElementById('edit_staff_email').value = staffEmail;
                document.getElementById('edit_staff_language').value = staffLanguage;
                
                document.getElementById('edit_staff_form').action = '/admin/staff/' + staffId + '/update';
                document.getElementById('edit_modal').classList.remove('hidden');
            });
        });

        // Modal kapatma
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalId = this.getAttribute('data-close-modal');
                document.getElementById(modalId).classList.add('hidden');
            });
        });
    });
</script>
@endpush

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'staff'])
@endsection

@section('content')
@if(session('success'))
<div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
    <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
    <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Personel</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Personel Listesi</x-ui.card-title>
        <x-ui.button id="create_staff_btn" class="gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Yeni Personel Ekle
        </x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>İsim</x-ui.table-head>
                    <x-ui.table-head>E-posta</x-ui.table-head>
                    <x-ui.table-head>Dil</x-ui.table-head>
                    <x-ui.table-head>Atanmış Oda Sayısı</x-ui.table-head>
                    <x-ui.table-head>Kayıt Tarihi</x-ui.table-head>
                    <x-ui.table-head>Aksiyon</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($staffMembers ?? [] as $staff)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $staff->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $staff->email }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @php
                            $languages = [
                                'tr' => 'Türkçe',
                                'en' => 'English',
                                'de' => 'Deutsch',
                                'fr' => 'Français',
                                'es' => 'Español',
                                'it' => 'Italiano',
                                'ru' => 'Русский',
                                'ar' => 'العربية',
                                'zh' => '中文',
                                'ja' => '日本語'
                            ];
                            $lang = $staff->language ?? 'tr';
                        @endphp
                        {{ $languages[$lang] ?? $lang }}
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ $staff->assignedRooms()->count() }} oda
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        {{ \Carbon\Carbon::parse($staff->created_at)->format('d.m.Y') }}
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-2">
                            <x-ui.button 
                                size="sm" 
                                variant="outline"
                                data-edit-staff
                                data-staff-id="{{ $staff->id }}"
                                data-staff-name="{{ $staff->name }}"
                                data-staff-email="{{ $staff->email }}"
                                data-staff-language="{{ $staff->language ?? 'tr' }}">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </x-ui.button>
                            <form action="{{ route('admin.staff.delete', $staff->id) }}" method="POST" onsubmit="return confirm('Bu personeli silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-ui.button size="sm" variant="outline" type="submit" class="text-red-600 hover:text-red-700">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                        Henüz personel yok. "Yeni Personel Ekle" butonuna tıklayarak personel ekleyin.
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

<!-- Personel Ekleme Modal -->
<div id="create_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Yeni Personel Ekle</h3>
            <button data-close-modal="create_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.staff.create') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">İsim *</label>
                <input type="text" name="name" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Personel adı soyadı">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">E-posta *</label>
                <input type="email" name="email" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="personel@example.com">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Şifre *</label>
                <input type="password" name="password" required minlength="8" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="En az 8 karakter">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Şifre Tekrar *</label>
                <input type="password" name="password_confirmation" required minlength="8" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Şifreyi tekrar girin">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Dil</label>
                <select name="language" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="tr">Türkçe</option>
                    <option value="en">English</option>
                    <option value="de">Deutsch</option>
                    <option value="fr">Français</option>
                    <option value="es">Español</option>
                    <option value="it">Italiano</option>
                    <option value="ru">Русский</option>
                    <option value="ar">العربية</option>
                    <option value="zh">中文</option>
                    <option value="ja">日本語</option>
                </select>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="create_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Oluştur</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Personel Düzenleme Modal -->
<div id="edit_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Personel Düzenle</h3>
            <button data-close-modal="edit_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="" method="POST" id="edit_staff_form">
            @csrf
            <input type="hidden" id="edit_staff_id" name="staff_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">İsim *</label>
                <input type="text" id="edit_staff_name" name="name" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">E-posta *</label>
                <input type="email" id="edit_staff_email" name="email" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                <input type="password" name="password" minlength="8" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="En az 8 karakter">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Şifre Tekrar</label>
                <input type="password" name="password_confirmation" minlength="8" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Şifreyi tekrar girin">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Dil</label>
                <select name="language" id="edit_staff_language" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="tr">Türkçe</option>
                    <option value="en">English</option>
                    <option value="de">Deutsch</option>
                    <option value="fr">Français</option>
                    <option value="es">Español</option>
                    <option value="it">Italiano</option>
                    <option value="ru">Русский</option>
                    <option value="ar">العربية</option>
                    <option value="zh">中文</option>
                    <option value="ja">日本語</option>
                </select>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="edit_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Kaydet</x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection

