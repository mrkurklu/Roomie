@extends('layouts.portal')

@section('title', 'Canlı Sohbet - Misafir Paneli')

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
    @include('guest.partials.sidebar', ['active' => 'chat'])
@endsection

@section('content')
<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Resepsiyon ile Sohbet</x-ui.card-title>
        <x-ui.badge>Çevrimiçi</x-ui.badge>
    </x-ui.card-header>
    <x-ui.card-content class="space-y-4">
        <div class="max-h-80 overflow-auto space-y-3">
            @forelse($messages ?? [] as $message)
            @if($message->from_user_id === auth()->id())
            <div class="flex justify-end">
                <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-primary text-primary-foreground">
                    <div class="font-medium text-xs opacity-70 mb-1">{{ $message->fromUser->name ?? 'Siz' }}</div>
                    {{ $message->display_content ?? $message->content }}
                    <div class="text-xs opacity-70 mt-1">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @else
            <div class="flex justify-start">
                <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-muted">
                    <div class="font-medium text-xs opacity-70 mb-1">{{ $message->fromUser->name ?? 'Resepsiyon' }}</div>
                    {{ $message->display_content ?? $message->content }}
                    <div class="text-xs opacity-70 mt-1">{{ $message->created_at->format('H:i') }}</div>
                </div>
            </div>
            @endif
            @empty
            <div class="text-sm text-muted-foreground text-center py-8">Henüz mesaj yok. İlk mesajınızı gönderin!</div>
            @endforelse
        </div>
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
        <form method="POST" action="{{ route('guest.chat.store') }}" class="flex gap-2">
            @csrf
            <x-ui.input name="content" placeholder="Mesajınızı yazın..." class="flex-1" required />
            <x-ui.button type="submit" class="gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Gönder
            </x-ui.button>
        </form>
    </x-ui.card-content>
</x-ui.card>

@if(isset($messages) && $messages->count() > 20)
<div class="mt-6">
    {{ $messages->links() }}
</div>
@endif
@endsection
