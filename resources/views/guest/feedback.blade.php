@extends('layouts.portal')

@section('title', 'Geri Bildirim - Misafir Paneli')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

@section('sidebar')
    @include('guest.partials.sidebar', ['active' => 'feedback'])
@endsection

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-8">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Geri Bildirim Gönder</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @if(session('success'))
                    <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-3 rounded-md bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200 text-sm mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('guest.feedback.store') }}">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Rezervasyon</label>
                        <select name="reservation_id" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="">Rezervasyon seçin</option>
                            @foreach($reservations ?? [] as $reservation)
                            <option value="{{ $reservation->id }}">
                                Oda {{ $reservation->room->room_number ?? 'N/A' }} - 
                                {{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d.m.Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Değerlendirme</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                <x-ui.button type="button" size="icon" variant="outline" class="rounded-full peer-checked:bg-primary peer-checked:text-primary-foreground">
                                    {{ $i }}
                                </x-ui.button>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Kategori</label>
                        <select name="category" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                            <option value="service">Hizmet</option>
                            <option value="cleanliness">Temizlik</option>
                            <option value="comfort">Konfor</option>
                            <option value="value">Değer</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Yorum</label>
                        <textarea name="comment" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm" placeholder="Deneyiminizi bizimle paylaşın..."></textarea>
                    </div>
                    <x-ui.button type="submit">Gönder</x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-4">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Önceki Geri Bildirimlerim</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-3">
                @forelse($feedbacks ?? [] as $feedback)
                <div class="p-3 rounded-xl border">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="{{ $i <= $feedback->rating ? 'star' : 'star' }}" 
                               class="w-4 h-4 {{ $i <= $feedback->rating ? 'fill-yellow-400 text-yellow-400' : 'text-muted-foreground' }}"></i>
                            @endfor
                        </div>
                        <x-ui.badge variant="outline">{{ ucfirst($feedback->category) }}</x-ui.badge>
                    </div>
                    @if($feedback->comment)
                    <div class="text-sm text-muted-foreground mt-2">{{ Str::limit($feedback->comment, 100) }}</div>
                    @endif
                    <div class="text-xs text-muted-foreground mt-2">{{ $feedback->created_at->format('d.m.Y') }}</div>
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-8">Henüz geri bildirim yok</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>

@if(isset($feedbacks) && $feedbacks->count() > 10)
<div class="mt-6">
    {{ $feedbacks->links() }}
</div>
@endif
@endsection
