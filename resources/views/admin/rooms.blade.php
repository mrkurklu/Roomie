@extends('layouts.portal')

@section('title', 'Odalar - Yönetim Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Personel atama modal açma
        document.querySelectorAll('[data-assign-staff]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const roomNumber = this.getAttribute('data-room-number');
                const currentStaffId = this.getAttribute('data-current-staff-id') || '';
                
                document.getElementById('assign_room_id').value = roomId;
                document.getElementById('assign_room_number').textContent = roomNumber;
                document.getElementById('assign_staff_id').value = currentStaffId;
                
                document.getElementById('assign_modal').classList.remove('hidden');
            });
        });

        // Oda düzenleme modal açma
        document.querySelectorAll('[data-edit-room]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const roomNumber = this.getAttribute('data-room-number');
                const roomTypeId = this.getAttribute('data-room-type-id');
                const roomStatus = this.getAttribute('data-room-status');
                
                document.getElementById('edit_room_id').value = roomId;
                document.getElementById('edit_room_number').value = roomNumber;
                document.getElementById('edit_room_type_id').value = roomTypeId;
                document.getElementById('edit_room_status').value = roomStatus;
                
                document.getElementById('edit_modal').classList.remove('hidden');
            });
        });

        // Oda oluşturma modal açma
        document.getElementById('create_rooms_btn')?.addEventListener('click', function() {
            document.getElementById('create_modal').classList.remove('hidden');
        });

        // Oda tipi oluşturma modal açma
        document.getElementById('create_roomtype_btn')?.addEventListener('click', function() {
            document.getElementById('create_roomtype_modal').classList.remove('hidden');
        });

        // Modal içinden oda tipi oluşturma
        document.getElementById('create_roomtype_from_modal')?.addEventListener('click', function() {
            document.getElementById('create_modal').classList.add('hidden');
            document.getElementById('create_roomtype_modal').classList.remove('hidden');
        });

        // Oda tipi düzenleme modal açma
        document.querySelectorAll('[data-edit-roomtype]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomTypeId = this.getAttribute('data-roomtype-id');
                const roomTypeName = this.getAttribute('data-roomtype-name');
                const roomTypeDesc = this.getAttribute('data-roomtype-desc') || '';
                const roomTypePrice = this.getAttribute('data-roomtype-price');
                const roomTypeCapacity = this.getAttribute('data-roomtype-capacity');
                
                document.getElementById('edit_roomtype_id').value = roomTypeId;
                document.getElementById('edit_roomtype_name').value = roomTypeName;
                document.getElementById('edit_roomtype_description').value = roomTypeDesc;
                document.getElementById('edit_roomtype_price').value = roomTypePrice;
                document.getElementById('edit_roomtype_capacity').value = roomTypeCapacity;
                
                document.getElementById('edit_roomtype_form').action = '/admin/room-types/' + roomTypeId + '/update';
                document.getElementById('edit_roomtype_modal').classList.remove('hidden');
            });
        });

        // Modal kapatma
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalId = this.getAttribute('data-close-modal');
                document.getElementById(modalId).classList.add('hidden');
            });
        });

        // Misafir geçmişi modal açma
        document.querySelectorAll('[data-guest-history]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                document.getElementById('history_room_id').value = roomId;
                document.getElementById('history_modal').classList.remove('hidden');
            });
        });
    });
</script>
@endpush

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'rooms'])
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

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Toplam Oda</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-green-600">{{ $stats['available'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Müsait</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-orange-600">{{ $stats['occupied'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Dolu</div>
        </x-ui.card-content>
    </x-ui.card>
    <x-ui.card class="border-none shadow-sm">
        <x-ui.card-content class="pt-6">
            <div class="text-2xl font-semibold text-red-600">{{ $stats['maintenance'] ?? 0 }}</div>
            <div class="text-sm text-muted-foreground mt-1">Bakımda</div>
        </x-ui.card-content>
    </x-ui.card>
</div>

<x-ui.card class="border-none shadow-sm mb-6">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Oda Yönetimi</x-ui.card-title>
        <div class="flex gap-2">
            <x-ui.button id="create_roomtype_btn" variant="outline" class="gap-2">
                <i data-lucide="tag" class="w-4 h-4"></i>
                Oda Tipi Oluştur
            </x-ui.button>
            <x-ui.button id="create_rooms_btn" class="gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Toplu Oda Oluştur
            </x-ui.button>
        </div>
    </x-ui.card-header>
</x-ui.card>

<!-- Oda Tipleri Listesi -->
@if(isset($roomTypes) && $roomTypes->count() > 0)
<x-ui.card class="border-none shadow-sm mb-6">
    <x-ui.card-header class="pb-2">
        <x-ui.card-title>Oda Tipleri</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Oda Tipi</x-ui.table-head>
                    <x-ui.table-head>Açıklama</x-ui.table-head>
                    <x-ui.table-head>Gece Fiyatı</x-ui.table-head>
                    <x-ui.table-head>Kapasite</x-ui.table-head>
                    <x-ui.table-head>Oda Sayısı</x-ui.table-head>
                    <x-ui.table-head>Aksiyon</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @foreach($roomTypes as $roomType)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">{{ $roomType->name }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        <span class="text-sm text-muted-foreground">{{ Str::limit($roomType->description ?? '-', 50) }}</span>
                    </x-ui.table-cell>
                    <x-ui.table-cell>{{ number_format($roomType->price_per_night, 2) }} ₺</x-ui.table-cell>
                    <x-ui.table-cell>{{ $roomType->capacity }} Kişi</x-ui.table-cell>
                    <x-ui.table-cell>{{ $roomType->rooms()->count() }} Oda</x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex gap-2">
                            <x-ui.button 
                                size="sm" 
                                variant="outline"
                                data-edit-roomtype
                                data-roomtype-id="{{ $roomType->id }}"
                                data-roomtype-name="{{ $roomType->name }}"
                                data-roomtype-desc="{{ $roomType->description ?? '' }}"
                                data-roomtype-price="{{ $roomType->price_per_night }}"
                                data-roomtype-capacity="{{ $roomType->capacity }}">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </x-ui.button>
                            <form action="{{ route('admin.roomtypes.delete', $roomType->id) }}" method="POST" onsubmit="return confirm('Bu oda tipini silmek istediğinizden emin misiniz? Bu oda tipine ait tüm odalar da silinecektir. Bu işlem geri alınamaz.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-ui.button size="sm" variant="outline" type="submit" class="text-red-600 hover:text-red-700">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforeach
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>
@endif

<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between gap-4">
        <x-ui.card-title>Oda Listesi</x-ui.card-title>
        <form method="GET" action="{{ route('admin.rooms') }}" class="flex gap-2 items-center" id="search_form">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground"></i>
                <x-ui.input 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Oda numarası veya tipi ara..." 
                    class="pl-9 w-64"
                    id="search_input" />
            </div>
            <select name="status" id="status_filter" class="rounded-md border border-input bg-background px-3 py-2 text-sm h-10">
                <option value="">Tüm Durumlar</option>
                <option value="available" {{ $statusFilter === 'available' ? 'selected' : '' }}>Müsait</option>
                <option value="occupied" {{ $statusFilter === 'occupied' ? 'selected' : '' }}>Dolu</option>
                <option value="maintenance" {{ $statusFilter === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
            </select>
            @if($search || $statusFilter)
            <a href="{{ route('admin.rooms') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors h-10 w-10 bg-secondary text-secondary-foreground hover:bg-secondary/80">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
            @endif
        </form>
    </x-ui.card-header>
    <x-ui.card-content>
        <x-ui.table>
            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head>Oda No</x-ui.table-head>
                    <x-ui.table-head>Oda Tipi</x-ui.table-head>
                    <x-ui.table-head>Durum</x-ui.table-head>
                    <x-ui.table-head>Aktif Misafir</x-ui.table-head>
                    <x-ui.table-head>Sorumlu Personel</x-ui.table-head>
                    <x-ui.table-head>Aksiyon</x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            <x-ui.table-body>
                @forelse($rooms ?? [] as $room)
                <x-ui.table-row>
                    <x-ui.table-cell class="font-medium">Oda {{ $room->room_number }}</x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($room->roomType)
                            {{ $room->roomType->name }}
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($room->status === 'available')
                            <x-ui.badge class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Müsait</x-ui.badge>
                        @elseif($room->status === 'occupied')
                            <x-ui.badge class="bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">Dolu</x-ui.badge>
                        @else
                            <x-ui.badge class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Bakımda</x-ui.badge>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($room->activeGuestStay)
                            <div>
                                <p class="font-medium text-sm">{{ $room->activeGuestStay->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-muted-foreground">
                                    {{ \Carbon\Carbon::parse($room->activeGuestStay->check_in)->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        @else
                            <span class="text-muted-foreground">-</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        @if($room->assignedStaff)
                            <span class="text-sm">{{ $room->assignedStaff->name }}</span>
                        @else
                            <span class="text-muted-foreground text-sm italic">Atanmamış</span>
                        @endif
                    </x-ui.table-cell>
                    <x-ui.table-cell>
                        <div class="flex flex-wrap gap-2">
                            <div class="flex flex-col items-center gap-1">
                                <x-ui.button 
                                    size="sm" 
                                    variant="outline"
                                    data-edit-room
                                    data-room-id="{{ $room->id }}"
                                    data-room-number="{{ $room->room_number }}"
                                    data-room-type-id="{{ $room->room_type_id }}"
                                    data-room-status="{{ $room->status }}">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </x-ui.button>
                                <span class="text-xs text-muted-foreground">Düzenle</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <x-ui.button 
                                    size="sm" 
                                    variant="outline"
                                    data-assign-staff
                                    data-room-id="{{ $room->id }}"
                                    data-room-number="{{ $room->room_number }}"
                                    data-current-staff-id="{{ $room->assigned_staff_id ?? '' }}">
                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                </x-ui.button>
                                <span class="text-xs text-muted-foreground">Personel Ata</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <x-ui.button 
                                    size="sm" 
                                    variant="outline"
                                    data-guest-history
                                    data-room-id="{{ $room->id }}">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </x-ui.button>
                                <span class="text-xs text-muted-foreground">Geçmiş</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <x-ui.button 
                                    size="sm" 
                                    variant="outline"
                                    data-add-guest-to-room
                                    data-room-id="{{ $room->id }}"
                                    data-room-number="{{ $room->room_number }}">
                                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                                </x-ui.button>
                                <span class="text-xs text-muted-foreground">Misafir Ekle</span>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <form action="{{ route('admin.rooms.delete', $room->id) }}" method="POST" onsubmit="return confirm('Bu odayı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button size="sm" variant="outline" type="submit" class="text-red-600 hover:text-red-700">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </x-ui.button>
                                </form>
                                <span class="text-xs text-muted-foreground">Sil</span>
                            </div>
                        </div>
                    </x-ui.table-cell>
                </x-ui.table-row>
                @empty
                <x-ui.table-row>
                    <x-ui.table-cell colspan="6" class="text-center py-8 text-muted-foreground">
                        @if($search || $statusFilter)
                            Arama kriterlerinize uygun oda bulunamadı.
                        @else
                            Henüz oda yok. "Toplu Oda Oluştur" butonuna tıklayarak odalar oluşturun.
                        @endif
                    </x-ui.table-cell>
                </x-ui.table-row>
                @endforelse
            </x-ui.table-body>
        </x-ui.table>
    </x-ui.card-content>
</x-ui.card>

<!-- Toplu Oda Oluşturma Modal -->
<div id="create_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Toplu Oda Oluştur</h3>
            <button data-close-modal="create_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.rooms.create') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Sayısı</label>
                <input type="number" name="room_count" min="1" max="500" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: 50">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Tipi</label>
                <select name="room_type_id" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="">Oda tipi seçin...</option>
                    @foreach($roomTypes ?? [] as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                    @endforeach
                </select>
                @if(empty($roomTypes) || $roomTypes->count() === 0)
                    <p class="text-xs text-muted-foreground mt-2">
                        Önce oda tipi oluşturmanız gerekiyor. 
                        <button type="button" id="create_roomtype_from_modal" class="text-primary-light dark:text-primary-dark underline">Oda Tipi Oluştur</button>
                    </p>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Önek (Opsiyonel)</label>
                <input type="text" name="prefix" maxlength="10" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: A, B, 1">
                <p class="text-xs text-muted-foreground mt-1">Oda numaralarının başına eklenecek önek (örn: "A" yazarsanız A101, A102, A103... oluşur)</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Başlangıç Numarası (Opsiyonel)</label>
                <input type="number" name="start_number" min="1" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: 101" value="1">
                <p class="text-xs text-muted-foreground mt-1">Oda numaralarının başlayacağı sayı (örn: 101 yazarsanız 101, 102, 103... oluşur)</p>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="create_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Oluştur</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Oda Düzenleme Modal -->
<div id="edit_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Oda Düzenle</h3>
            <button data-close-modal="edit_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="" method="POST" id="edit_form">
            @csrf
            <input type="hidden" id="edit_room_id" name="room_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Numarası</label>
                <input type="text" id="edit_room_number" name="room_number" required maxlength="50" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Tipi</label>
                <select name="room_type_id" id="edit_room_type_id" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    @foreach($roomTypes ?? [] as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Durum</label>
                <select name="status" id="edit_room_status" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="available">Müsait</option>
                    <option value="occupied">Dolu</option>
                    <option value="maintenance">Bakımda</option>
                </select>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="edit_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Kaydet</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Personel Atama Modal -->
<div id="assign_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Personel Ata</h3>
            <button data-close-modal="assign_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.rooms.assignStaff') }}" method="POST">
            @csrf
            <input type="hidden" id="assign_room_id" name="room_id" required>
            
            <div class="mb-4">
                <p class="text-sm text-muted-foreground mb-2">Oda: <span id="assign_room_number" class="font-medium text-foreground"></span></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Personel Seçin</label>
                <select name="staff_id" id="assign_staff_id" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                    <option value="">Personel atamasını kaldır</option>
                    @foreach($staffMembers ?? [] as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="assign_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Kaydet</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Oda Tipi Oluşturma Modal -->
<div id="create_roomtype_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Oda Tipi Oluştur</h3>
            <button data-close-modal="create_roomtype_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.roomtypes.create') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Tipi Adı *</label>
                <input type="text" name="name" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: Tek Kişilik, Çift Kişilik, Suit">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Açıklama (Opsiyonel)</label>
                <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Oda tipi hakkında açıklama..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Gece Fiyatı (₺) *</label>
                <input type="number" name="price_per_night" step="0.01" min="0" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: 150.00">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Kapasite (Kişi Sayısı) *</label>
                <input type="number" name="capacity" min="1" max="20" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Örn: 1, 2, 4">
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="create_roomtype_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Oluştur</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Oda Tipi Düzenleme Modal -->
<div id="edit_roomtype_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Oda Tipi Düzenle</h3>
            <button data-close-modal="edit_roomtype_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="" method="POST" id="edit_roomtype_form">
            @csrf
            <input type="hidden" id="edit_roomtype_id" name="roomtype_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Oda Tipi Adı *</label>
                <input type="text" id="edit_roomtype_name" name="name" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Açıklama (Opsiyonel)</label>
                <textarea id="edit_roomtype_description" name="description" rows="3" maxlength="1000" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Gece Fiyatı (₺) *</label>
                <input type="number" id="edit_roomtype_price" name="price_per_night" step="0.01" min="0" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Kapasite (Kişi Sayısı) *</label>
                <input type="number" id="edit_roomtype_capacity" name="capacity" min="1" max="20" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
            </div>

            <div class="flex gap-2 justify-end">
                <x-ui.button type="button" variant="outline" data-close-modal="edit_roomtype_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Kaydet</x-ui.button>
            </div>
        </form>
    </div>
</div>

<!-- Misafir Geçmişi Modal -->
<div id="history_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Misafir Geçmişi</h3>
            <button data-close-modal="history_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="history_content">
            <p class="text-muted-foreground text-center py-4">Yükleniyor...</p>
        </div>
    </div>
</div>

<!-- Odaya Misafir Ekleme Modal -->
<div id="add_guest_modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-surface-dark rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Odaya Misafir Ekle</h3>
            <button data-close-modal="add_guest_modal" class="text-muted-foreground hover:text-foreground">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.guests.create') }}" method="POST">
            @csrf
            <input type="hidden" id="add_guest_room_id" name="room_id">
            
            <div class="mb-4 p-3 rounded-lg bg-primary-light/10 dark:bg-primary-dark/10 border border-primary-light/20 dark:border-primary-dark/20">
                <p class="text-sm font-medium text-text-light dark:text-text-dark">Seçili Oda:</p>
                <p class="text-lg font-semibold text-primary-light dark:text-primary-dark" id="add_guest_room_number"></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">İsim *</label>
                <input type="text" name="name" required maxlength="255" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Misafir adı soyadı">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">E-posta *</label>
                <input type="email" name="email" required class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="misafir@example.com">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">TC Kimlik No *</label>
                <input type="text" name="tc_no" required maxlength="11" minlength="11" pattern="[0-9]{11}" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="11 haneli TC numarası">
                <p class="text-xs text-muted-foreground mt-1">TC numarası şifre olarak kullanılacaktır.</p>
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
                <x-ui.button type="button" variant="outline" data-close-modal="add_guest_modal">İptal</x-ui.button>
                <x-ui.button type="submit">Oluştur ve Check-in Yap</x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
    // Edit form action'ını güncelle
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('edit_form');
        const editRoomId = document.getElementById('edit_room_id');
        
        if (editForm && editRoomId) {
            document.querySelectorAll('[data-edit-room]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-room-id');
                    editForm.action = '/admin/rooms/' + roomId + '/update';
                });
            });
        }

        // Arama formu otomatik submit
        const searchForm = document.getElementById('search_form');
        const searchInput = document.getElementById('search_input');
        const statusFilter = document.getElementById('status_filter');
        
        if (searchInput) {
            // Enter tuşu ile submit
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }
        
        if (statusFilter) {
            // Durum filtresi değiştiğinde otomatik submit
            statusFilter.addEventListener('change', function() {
                searchForm.submit();
            });
        }

        // Odaya misafir ekleme modal açma
        document.querySelectorAll('[data-add-guest-to-room]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const roomNumber = this.getAttribute('data-room-number');
                
                document.getElementById('add_guest_room_id').value = roomId;
                document.getElementById('add_guest_room_number').textContent = 'Oda ' + roomNumber;
                document.getElementById('add_guest_modal').classList.remove('hidden');
            });
        });

        // Misafir geçmişi yükle
        document.querySelectorAll('[data-guest-history]').forEach(btn => {
            btn.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                const historyContent = document.getElementById('history_content');
                historyContent.innerHTML = '<p class="text-muted-foreground text-center py-4">Yükleniyor...</p>';
                
                fetch('/admin/rooms/' + roomId + '/history')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.guestStays && data.guestStays.length > 0) {
                            let html = '<div class="space-y-3">';
                            data.guestStays.forEach(stay => {
                                html += `
                                    <div class="border rounded-lg p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-medium">${stay.user_name || 'N/A'}</p>
                                                <p class="text-xs text-muted-foreground">${stay.user_email || ''}</p>
                                            </div>
                                            <span class="text-xs px-2 py-1 rounded ${stay.status === 'checked_in' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">${stay.status === 'checked_in' ? 'Aktif' : 'Çıkış Yapıldı'}</span>
                                        </div>
                                        <div class="text-sm space-y-1">
                                            <p><span class="text-muted-foreground">Check-in:</span> ${stay.check_in}</p>
                                            ${stay.check_out ? `<p><span class="text-muted-foreground">Check-out:</span> ${stay.check_out}</p>` : ''}
                                        </div>
                                    </div>
                                `;
                            });
                            html += '</div>';
                            historyContent.innerHTML = html;
                        } else {
                            historyContent.innerHTML = '<p class="text-muted-foreground text-center py-4">Bu odada henüz misafir konaklamamış.</p>';
                        }
                    })
                    .catch(error => {
                        historyContent.innerHTML = '<p class="text-red-600 text-center py-4">Geçmiş yüklenirken bir hata oluştu.</p>';
                    });
            });
        });
    });
</script>
@endsection
