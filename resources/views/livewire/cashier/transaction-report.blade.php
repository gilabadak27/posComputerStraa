<div class="space-y-6">
    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Omset -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl">
                💰
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendapatan</p>
                <h3 class="text-xl font-black text-slate-800 mt-0.5">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xl">
                🧾
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Transaksi</p>
                <h3 class="text-xl font-black text-slate-800 mt-0.5">{{ number_format($stats['total_count']) }} Trx</h3>
            </div>
        </div>

        <!-- Rata-rata Transaksi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xl">
                📊
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-rata / Trx</p>
                <h3 class="text-xl font-black text-slate-800 mt-0.5">Rp {{ number_format($stats['avg_amount'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-center">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Metode Pembayaran</p>
            <div class="flex items-center justify-between text-xs font-bold">
                <span class="text-slate-600">💵 Cash: <strong class="text-slate-900">{{ $stats['cash_count'] }}</strong></span>
                <span class="text-indigo-600">📲 QRIS: <strong>{{ $stats['qris_count'] }}</strong></span>
                <span class="text-sky-600">💳 Debit: <strong>{{ $stats['debit_count'] }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <!-- Date Preset & Filters Bar -->
        <div class="p-5 border-b border-slate-100 space-y-4 bg-slate-50/50">
            <!-- Preset Date Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Periode:</span>
                    <button 
                        wire:click="selectDatePreset('all')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Semua
                    </button>
                    <button 
                        wire:click="selectDatePreset('today')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'today' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Hari Ini
                    </button>
                    <button 
                        wire:click="selectDatePreset('yesterday')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'yesterday' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Kemarin
                    </button>
                    <button 
                        wire:click="selectDatePreset('this_week')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'this_week' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Minggu Ini
                    </button>
                    <button 
                        wire:click="selectDatePreset('this_month')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'this_month' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Bulan Ini
                    </button>
                    <button 
                        wire:click="selectDatePreset('custom')"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $datePreset === 'custom' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Custom Range
                    </button>
                </div>

                <!-- Print / Export Action -->
                <button 
                    onclick="window.print()" 
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <span>🖨️</span>
                    <span>Cetak Laporan</span>
                </button>
            </div>

            <!-- Custom Date Pickers & Search Controls -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                <!-- Custom Date Range -->
                @if ($datePreset === 'custom')
                    <div class="flex items-center gap-2 md:col-span-2">
                        <input 
                            type="date" 
                            wire:model.live="startDate"
                            class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                        />
                        <span class="text-xs text-slate-400 font-bold">s/d</span>
                        <input 
                            type="date" 
                            wire:model.live="endDate"
                            class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                        />
                    </div>
                @endif

                <!-- Search Input -->
                <div class="relative flex-1 {{ $datePreset !== 'custom' ? 'md:col-span-2' : '' }}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        🔍
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari No. Invoice, Nama Kasir, atau Pelanggan..."
                        class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                    />
                </div>

                <!-- Payment Method Filter -->
                <div>
                    <select 
                        wire:model.live="paymentMethod"
                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        <option value="all">Semua Pembayaran</option>
                        <option value="cash">Cash (Tunai)</option>
                        <option value="qris">QRIS</option>
                        <option value="debit">Kartu Debit</option>
                        <option value="credit">Kartu Kredit</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/60 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-5">No. Invoice</th>
                        <th class="py-3.5 px-5">Waktu Transaksi</th>
                        <th class="py-3.5 px-5">Kasir</th>
                        <th class="py-3.5 px-5">Pelanggan</th>
                        <th class="py-3.5 px-5 text-center">Metode</th>
                        <th class="py-3.5 px-5 text-right">Total Nominal</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($transactions as $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Invoice # -->
                            <td class="py-4 px-5 font-mono font-bold text-indigo-600">
                                {{ $trx->invoice_number }}
                            </td>

                            <!-- Date & Time -->
                            <td class="py-4 px-5 text-xs font-medium text-slate-600">
                                <div>{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="text-slate-400">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Cashier Name -->
                            <td class="py-4 px-5">
                                <span class="font-bold text-slate-800">{{ $trx->user->name ?? 'Kasir' }}</span>
                            </td>

                            <!-- Customer Name -->
                            <td class="py-4 px-5">
                                @if ($trx->customer)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100">
                                        👤 {{ $trx->customer->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Umum (Non-Member)</span>
                                @endif
                            </td>

                            <!-- Payment Method Badge -->
                            <td class="py-4 px-5 text-center">
                                @php
                                    $methodColors = [
                                        'cash' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'qris' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'debit' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'credit' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-black uppercase rounded-lg border {{ $methodColors[$trx->payment_method] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ $trx->payment_method }}
                                </span>
                            </td>

                            <!-- Total Nominal -->
                            <td class="py-4 px-5 text-right font-black text-slate-800">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right space-x-1.5">
                                <button 
                                    wire:click="showDetails({{ $trx->id }})"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                    Detail
                                </button>
                                <button 
                                    wire:click="printReceipt({{ $trx->id }})"
                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs rounded-lg transition-colors">
                                    Struk
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 font-medium">
                                <p class="text-3xl mb-2">🧾</p>
                                <p class="text-sm">Tidak ditemukan data transaksi untuk filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- DETAIL TRANSACTION MODAL -->
    @if ($showDetailModal && $selectedTransaction)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 animate-in fade-in zoom-in duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Detail Transaksi</h3>
                        <p class="text-xs font-mono font-bold text-indigo-600">{{ $selectedTransaction->invoice_number }}</p>
                    </div>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <!-- Transaction Meta -->
                <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl text-xs space-y-1">
                    <div>
                        <span class="text-slate-400 block font-semibold">Tanggal & Waktu</span>
                        <strong class="text-slate-700">{{ $selectedTransaction->created_at->format('d/m/Y H:i') }} WIB</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Kasir</span>
                        <strong class="text-slate-700">{{ $selectedTransaction->user->name ?? 'Kasir' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Pelanggan</span>
                        <strong class="text-slate-700">{{ $selectedTransaction->customer->name ?? 'Umum (Non-Member)' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block font-semibold">Metode Pembayaran</span>
                        <strong class="text-indigo-600 uppercase">{{ $selectedTransaction->payment_method }}</strong>
                    </div>
                </div>

                <!-- Purchased Items List -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Item Produk</h4>
                    <div class="border border-slate-200 rounded-xl divide-y divide-slate-100 max-h-48 overflow-y-auto">
                        @foreach ($selectedTransaction->items as $item)
                            <div class="p-3 flex justify-between items-center text-xs">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $item->product->name ?? 'Produk' }}</p>
                                    <p class="text-slate-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <span class="font-bold text-slate-800">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="space-y-1.5 pt-2 text-xs border-t border-slate-100">
                    <div class="flex justify-between font-bold text-slate-800 text-sm">
                        <span>Total Nominal</span>
                        <span>Rp {{ number_format($selectedTransaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Jumlah Dibayar</span>
                        <span>Rp {{ number_format($selectedTransaction->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-emerald-600">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($selectedTransaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 border-t border-slate-100 flex gap-2">
                    <button 
                        wire:click="printReceipt({{ $selectedTransaction->id }})"
                        class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-colors shadow-xs flex items-center justify-center gap-1.5">
                        <span>🖨️</span>
                        <span>Cetak Struk Ulangan</span>
                    </button>
                    <button 
                        wire:click="closeDetailModal" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Receipt Modal Overlay from Cashier -->
    @livewire('cashier.receipt-modal')
</div>
