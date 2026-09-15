<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
                <span class="text-2xl">📈</span>
                <span>Laporan Transaksi (Kasir POS)</span>
            </h2>
            <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">
                Transaction Reports
            </span>
        </div>
    </x-slot>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
        @livewire('cashier.transaction-report')
    </div>
</x-app-layout>
