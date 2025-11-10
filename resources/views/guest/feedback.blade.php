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
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-white">Geri Bildirim Gönder</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @if(session('success'))
                    <div class="p-3 rounded-md bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200 text-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-3 rounded-md bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="p-3 rounded-md bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm mb-4">
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
                        <label class="text-sm font-medium text-white">Değerlendirme</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden peer">
                                <button type="button" class="w-10 h-10 rounded-full second-color hover:bg-[#d4c18a] transition-all duration-200 hover:scale-105 peer-checked:third-color peer-checked:text-first-color dark:peer-checked:text-blue-400 peer-checked:shadow-md text-first-color dark:text-blue-400 font-medium flex items-center justify-center">
                                    {{ $i }}
                                </button>
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Kategori</label>
                        <select name="category" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-first-color dark:text-blue-400">
                            <option value="service">Hizmet</option>
                            <option value="cleanliness">Temizlik</option>
                            <option value="comfort">Konfor</option>
                            <option value="value">Değer</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Yorum</label>
                        <textarea name="comment" rows="4" class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-first-color dark:text-blue-400" placeholder="Deneyiminizi bizimle paylaşın..."></textarea>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-md third-color hover:bg-third-color/90 dark:hover:bg-yellow-600 transition-all duration-300 shadow-sm hover:shadow-xl hover:scale-105 hover:-translate-y-1 active:scale-100 text-first-color dark:text-blue-400 font-medium">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Gönder
                    </button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-4">
        <x-ui.card class="border-2 border-white/20 shadow-sm">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title class="text-white">Önceki Geri Bildirimlerim</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-3">
                @forelse($feedbacks ?? [] as $feedback)
                <div class="p-3 rounded-xl border border-white/20 bg-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                            <i data-lucide="{{ $i <= $feedback->rating ? 'star' : 'star' }}" 
                               class="w-4 h-4 {{ $i <= $feedback->rating ? 'fill-third-color text-third-color' : 'text-white/40' }}"></i>
                            @endfor
                        </div>
                        <x-ui.badge variant="outline">{{ ucfirst($feedback->category) }}</x-ui.badge>
                    </div>
                    @if($feedback->comment)
                    <div class="text-sm text-white/80 mt-2">{{ Str::limit($feedback->comment, 100) }}</div>
                    @endif
                    <div class="text-xs text-white/60 mt-2">{{ $feedback->created_at->format('d.m.Y') }}</div>
                </div>
                @empty
                <div class="text-sm text-white/70 text-center py-8">Henüz geri bildirim yok</div>
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
