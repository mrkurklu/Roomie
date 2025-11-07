@extends('layouts.portal')

@section('title', 'Mesajlar - Personel Paneli')

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
    @include('staff.partials.sidebar', ['active' => 'inbox'])
@endsection

@section('content')
@if($unreadCount > 0)
<div class="mb-4">
    <x-ui.card class="border-none shadow-sm bg-primary/10">
        <x-ui.card-content class="pt-6">
            <div class="flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                <div class="text-sm font-medium">{{ $unreadCount }} okunmamış mesajınız var</div>
            </div>
        </x-ui.card-content>
    </x-ui.card>
</div>
@endif

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-5">
        <x-ui.card class="border-none shadow-sm h-full">
            <x-ui.card-header class="pb-2">
                <x-ui.card-title>Sohbetler</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-2 max-h-[600px] overflow-y-auto">
                @forelse($messages ?? [] as $message)
                <div class="p-3 rounded-lg hover:bg-accent cursor-pointer {{ (!isset($message->is_read) || !$message->is_read) ? 'bg-accent/50' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="h-7 w-7 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-medium">{{ strtoupper(substr($message->fromUser->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium truncate">{{ $message->fromUser->name ?? 'Bilinmeyen' }}</div>
                                @if(!isset($message->is_read) || !$message->is_read)
                                <span class="h-2 w-2 bg-primary rounded-full flex-shrink-0"></span>
                                @endif
                            </div>
                            <div class="text-xs text-muted-foreground truncate mt-1">{{ Str::limit($message->subject ?? $message->content, 40) }}</div>
                            <div class="text-xs text-muted-foreground mt-1">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-sm text-muted-foreground text-center py-8">Henüz mesaj yok</div>
                @endforelse
            </x-ui.card-content>
        </x-ui.card>
    </div>
    <div class="col-span-12 lg:col-span-7 space-y-4">
        <x-ui.card class="border-none shadow-sm">
            <x-ui.card-header class="pb-2 flex items-center justify-between">
                <x-ui.card-title>Mesaj Detayı</x-ui.card-title>
                <x-ui.badge>Canlı</x-ui.badge>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4 max-h-[420px] overflow-auto">
                @if(isset($messages) && $messages->count() > 0)
                    @php $selectedMessage = $messages->first(); @endphp
                    <div class="flex justify-start">
                        <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-muted">
                            <div class="font-medium text-xs opacity-70 mb-1">{{ $selectedMessage->fromUser->name ?? 'Bilinmeyen' }}</div>
                            {{ $selectedMessage->display_content ?? $selectedMessage->content }}
                        </div>
                    </div>
                    @if($selectedMessage->toUser)
                    <div class="flex justify-end">
                        <div class="max-w-[75%] rounded-2xl p-3 text-sm bg-primary text-primary-foreground">
                            <div class="font-medium text-xs opacity-70 mb-1">{{ $selectedMessage->toUser->name ?? 'Bilinmeyen' }}</div>
                            {{ $selectedMessage->display_content ?? $selectedMessage->content }}
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-sm text-muted-foreground text-center py-8">Mesaj seçin</div>
                @endif
            </x-ui.card-content>
        </x-ui.card>
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
        <form method="POST" action="{{ route('staff.inbox.store') }}" class="flex gap-2">
            @csrf
            <input type="hidden" name="to_user_id" value="{{ isset($messages) && $messages->count() > 0 ? $messages->first()->from_user_id : '' }}" />
            <x-ui.input name="content" placeholder="Yanıt yazın..." class="flex-1" required />
            <x-ui.button type="submit" class="gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Gönder
            </x-ui.button>
        </form>
    </div>
</div>

@if(isset($messages) && $messages->count() > 15)
<div class="mt-6">
    {{ $messages->links() }}
</div>
@endif
@endsection
