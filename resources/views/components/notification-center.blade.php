<x-ui.card class="border-none shadow-sm">
    <x-ui.card-header class="pb-2 flex items-center justify-between">
        <x-ui.card-title>Bildirim Merkezi</x-ui.card-title>
        <x-ui.button variant="outline" x-data="{ connected: false }" @click="connected = !connected" x-text="connected ? 'Bağlı' : 'Bağlan'"></x-ui.button>
    </x-ui.card-header>
    <x-ui.card-content class="flex gap-2 overflow-x-auto">
        <div class="px-3 py-2 rounded-xl border text-sm whitespace-nowrap">
            (305) Ek yastık talebi oluşturuldu • <span class="opacity-60">19:02</span>
        </div>
        <div class="px-3 py-2 rounded-xl border text-sm whitespace-nowrap">
            (902) TV arızası atandı • <span class="opacity-60">18:51</span>
        </div>
    </x-ui.card-content>
</x-ui.card>

