@extends('layouts.portal')

@section('title', 'Misafirler - Yönetim Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Check-in modal açma
        document.querySelectorAll('[data-checkin]').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                document.getElementById('checkin_user_id').value = userId;
                document.getElementById('checkin_user_name').textContent = userName;
                document.getElementById('checkin_modal').classList.remove('hidden');
            });
        });

        // Check-out modal açma
        document.querySelectorAll('[data-checkout]').forEach(btn => {
            btn.addEventListener('click', function() {
                const stayId = this.getAttribute('data-stay-id');
                const userName = this.getAttribute('data-user-name');
                const roomNumber = this.getAttribute('data-room-number');
                document.getElementById('checkout_stay_id').value = stayId;
                document.getElementById('checkout_user_name').textContent = userName;
                document.getElementById('checkout_room_number').textContent = roomNumber;
                document.getElementById('checkout_modal').classList.remove('hidden');
            });
        });

        // Misafir ekleme modal açma
        document.getElementById('create_guest_btn')?.addEventListener('click', function() {
            document.getElementById('create_guest_modal').classList.remove('hidden');
        });

        // Validation hataları varsa modal'ı açık tut
        @if($errors->any() && old('_token'))
            document.getElementById('create_guest_modal')?.classList.remove('hidden');
        @endif

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
    @include('admin.partials.sidebar', ['active' => 'guests'])
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

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Misafir</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['active'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Aktif Misafir</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Misafir Listesi</x-ui.card-title>
        <x-ui.button id="create_guest_btn" class="gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Yeni Misafir Ekle
        </x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>İsim</x-ui.table-head>
                    <x-ui.table-head>E-posta</x-ui.table-head>
                    <x-ui.table-head>Oda</x-ui.table-head>
                    <x-ui.table-head>Check-in</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Aksiyon</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($guests ?? [] as $guest)
                @php
                    $activeStay = $guest->activeGuestStay;
                @endphp
                <x-ui.table-row>
                    <x-ui.table-cell>{{ $guest->name }}</x-ui.table-cell>
                    <x-ui.table-cell>{{ $guest->email }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($activeStay && $activeStay->room)
                            <span class="font-medium">Oda {{ $activeStay->room->room_number }}</span>
                            @if($activeStay->room->assignedStaff)
                                <div class="text-xs text-muted-foreground mt-1">
                                    Personel: {{ $activeStay->room->assignedStaff->name }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($activeStay)
                            {{ \Carbon\Carbon::parse($activeStay->check_in)->format('d.m.Y H:i') }}
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($activeStay)
                            <x-ui.badge class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Konaklıyor</x-ui.badge>
                        @else
                            <x-ui.badge variant="outline">Pasif</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-2">
                            @if($activeStay)
                                <x-ui.button 
                                    size="sm" 
                                    variant="outline" 
                                    data-checkout
                                    data-stay-id="{{ $activeStay->id }}"
                                    data-user-name="{{ $guest->name }}"
                                    data-room-number="{{ $activeStay->room->room_number ?? 'N/A' }}"
                                    class="text-orange-600 hover:text-orange-700">
                                    Check-out
                                </x-ui.button>
                            @else
                                <x-ui.button 
                                    size="sm" 
                                    class="gap-2"
                                    data-checkin
                                    data-user-id="{{ $guest->id }}"
                                    data-user-name="{{ $guest->name }}">
                                    <i data-lucide="log-in" class="w-4 h-4"></i>
                                    Check-in
                                </x-ui.button>
                            @endif
                            <a href="{{ route('admin.messages', ['to_user_id' => $guest->id]) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors h-8 px-3 py-1 bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                Mesaj
                            </a>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                        Henüz misafir yok
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

@if(isset($guests) && $guests->hasPages())
<div class="mt-6">
    {{ $guests->links() }}
</div>
@endif

<!-- Check-in Modal -->
<div id="checkin_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Check-in Yap</h3>
            <button data-close-modal="checkin_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.guests.checkin') }}" method="POST">
            @csrf
            <input type="hidden" id="checkin_user_id" name="user_id" required>
            
            <div class="mb-4">
                <p class="text-sm text-muted-foreground mb-2">Misafir: <span id="checkin_user_name" class="font-medium text-foreground"></span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Seçin</label>
                <select name="room_id" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="">Oda seçin...</option>
                    @foreach($rooms ?? [] as $room)
                        <option value="{{ $room->id }}">
                            Oda {{ $room->room_number }} 
                            @if($room->roomType)
                                - {{ $room->roomType->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Check-in Tarihi</label>
                <input type="datetime-local" name="check_in" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Notlar (Opsiyonel)</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="checkin_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Check-in Yap</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Check-out Modal -->
<div id="checkout_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Check-out Yap</h3>
            <button data-close-modal="checkout_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="" method="POST" id="checkout_form">
            @csrf
            
            <div class="mb-4">
                <p class="text-sm text-muted-foreground mb-1">Misafir: <span id="checkout_user_name" class="font-medium text-foreground"></span></p>
                <p class="text-sm text-muted-foreground">Oda: <span id="checkout_room_number" class="font-medium text-foreground"></span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Check-out Tarihi</label>
                <input type="datetime-local" name="check_out" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Notlar (Opsiyonel)</label>
                <textarea name="notes" rows="3" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="checkout_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Check-out Yap</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Misafir Ekleme Modal -->
<div id="create_guest_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Yeni Misafir Ekle</h3>
            <button data-close-modal="create_guest_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.guests.create') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-200">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">İsim *</label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('name') border-red-500 @enderror" placeholder="Misafir adı soyadı">
                @error('name')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">E-posta *</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('email') border-red-500 @enderror" placeholder="misafir@example.com">
                @error('email')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">TC Kimlik No *</label>
                <input type="text" name="tc_no" value="{{ old('tc_no') }}" required maxlength="11" minlength="11" pattern="[0-9]{11}" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('tc_no') border-red-500 @enderror" placeholder="11 haneli TC numarası">
                <p class="text-xs text-muted-foreground mt-1">TC numarası şifre olarak kullanılacaktır.</p>
                @error('tc_no')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda *</label>
                <select name="room_id" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('room_id') border-red-500 @enderror">
                    <option value="">Oda seçin</option>
                    @foreach($rooms ?? [] as $room)
                        @if($room->status === 'available' || $room->status === 'occupied')
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                Oda {{ $room->room_number }}
                                @if($room->roomType)
                                    - {{ $room->roomType->name }}
                                @endif
                                @if($room->status === 'occupied')
                                    (Dolu)
                                @endif
                            </option>
                        @endif
                    @endforeach
                </select>
                <p class="text-xs text-muted-foreground mt-1">Misafir seçilen odaya otomatik olarak check-in yapılır.</p>
                @error('room_id')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Dil</label>
                <select name="language" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="tr" {{ old('language', 'tr') == 'tr' ? 'selected' : '' }}>Türkçe</option>
                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>Deutsch</option>
                    <option value="fr" {{ old('language') == 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="es" {{ old('language') == 'es' ? 'selected' : '' }}>Español</option>
                    <option value="it" {{ old('language') == 'it' ? 'selected' : '' }}>Italiano</option>
                    <option value="ru" {{ old('language') == 'ru' ? 'selected' : '' }}>Русский</option>
                    <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>العربية</option>
                    <option value="zh" {{ old('language') == 'zh' ? 'selected' : '' }}>中文</option>
                    <option value="ja" {{ old('language') == 'ja' ? 'selected' : '' }}>日本語</option>
                </select>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="create_guest_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Oluştur</x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
    // Check-out form action'ını güncelle
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout_form');
        
        if (checkoutForm) {
            document.querySelectorAll('[data-checkout]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const stayId = this.getAttribute('data-stay-id');
                    checkoutForm.action = '/admin/guests/' + stayId + '/check-out';
                });
            });
        }
    });
</script>
@endsection
