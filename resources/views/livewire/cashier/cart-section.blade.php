<div class="bg-white rounded-2xl border border-gray-200/90 shadow-sm p-6 space-y-6">
    
    <!-- Cart Header -->
    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
            </svg>
            <h2 class="text-base font-bold text-gray-900">Keranjang Belanja</h2>
            <span class="px-2 py-0.5 text-xs font-extrabold bg-indigo-100 text-indigo-700 rounded-full">
                {{ count($cart) }}
            </span>
        </div>

        @if (count($cart) > 0)
            <button wire:click="clearCart" class="text-xs font-semibold text-rose-500 hover:text-rose-700 hover:underline">
                Kosongkan
            </button>
        @endif
    </div>

    <!-- Alert Messages -->
    @if ($errorMessage)
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center justify-between">
            <span>{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', null)" class="text-rose-400 hover:text-rose-600">✕</button>
        </div>
    @endif

    @if ($successMessage)
        <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center justify-between">
            <span>{{ $successMessage }}</span>
            <button wire:click="$set('successMessage', null)" class="text-emerald-400 hover:text-emerald-600">✕</button>
        </div>
    @endif

    <!-- Cart Items List -->
    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 scrollbar-thin">
        @forelse ($cart as $productId => $item)
            <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100">
                <div class="flex-1 pr-2">
                    <h4 class="text-xs font-bold text-gray-900 line-clamp-1">{{ $item['name'] }}</h4>
                    <span class="text-[11px] text-gray-500">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                </div>

                <!-- Quantity Controls -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden shadow-xs">
                        <button wire:click="updateQuantity({{ $productId }}, -1)" class="px-2 py-1 hover:bg-gray-100 text-gray-700 font-bold text-xs transition-colors">-</button>
                        <span class="px-2 py-1 text-xs font-extrabold text-gray-900 min-w-[22px] text-center">{{ $item['quantity'] }}</span>
                        <button wire:click="updateQuantity({{ $productId }}, 1)" class="px-2 py-1 hover:bg-gray-100 text-gray-700 font-bold text-xs transition-colors">+</button>
                    </div>
                    <button wire:click="removeFromCart({{ $productId }})" class="p-1 text-gray-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-400 text-xs">
                Keranjang belanja masih kosong.<br>Klik tombol <strong>+ Tambah</strong> pada produk.
            </div>
        @endforelse
    </div>

    <!-- Summary Details & Checkout -->
    <div class="pt-4 border-t border-gray-100 space-y-4">
        
        <!-- Total Price -->
        <div class="flex justify-between items-center bg-indigo-50/80 p-3.5 rounded-xl border border-indigo-100">
            <span class="text-xs font-bold text-indigo-900 uppercase">Total Bayar</span>
            <span class="text-xl font-black text-indigo-700 tracking-tight">
                Rp {{ number_format($this->total, 0, ',', '.') }}
            </span>
        </div>

        <!-- Payment Method -->
        <div>
            <label class="text-xs font-bold text-gray-700 block mb-1.5">Metode Pembayaran</label>
            <div class="grid grid-cols-3 gap-2">
                <button 
                    wire:click="$set('paymentMethod', 'cash')" 
                    class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all {{ $paymentMethod === 'cash' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    💵 Cash
                </button>
                <button 
                    wire:click="$set('paymentMethod', 'qris')" 
                    class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all {{ $paymentMethod === 'qris' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    📱 QRIS
                </button>
                <button 
                    wire:click="$set('paymentMethod', 'debit')" 
                    class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all {{ $paymentMethod === 'debit' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100' }}">
                    💳 Debit
                </button>
            </div>
        </div>

        <!-- Cash Input -->
        @if ($paymentMethod === 'cash')
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-1">Uang Diterima (Rp)</label>
                <input 
                    type="number" 
                    wire:model.live="paidAmount" 
                    placeholder="Masukkan jumlah tunai..." 
                    class="w-full px-3.5 py-2.5 bg-gray-50 rounded-xl border border-gray-200 text-sm font-extrabold text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all outline-none"
                />

                <div class="flex flex-wrap gap-1.5 mt-2">
                    <button wire:click="$set('paidAmount', {{ $this->total }})" class="px-2 py-0.5 bg-gray-100 hover:bg-indigo-50 text-indigo-700 text-[11px] font-bold rounded-md">Uang Pas</button>
                    <button wire:click="$set('paidAmount', 50000)" class="px-2 py-0.5 bg-gray-100 hover:bg-indigo-50 text-gray-700 text-[11px] font-bold rounded-md">50k</button>
                    <button wire:click="$set('paidAmount', 100000)" class="px-2 py-0.5 bg-gray-100 hover:bg-indigo-50 text-gray-700 text-[11px] font-bold rounded-md">100k</button>
                    <button wire:click="$set('paidAmount', 200000)" class="px-2 py-0.5 bg-gray-100 hover:bg-indigo-50 text-gray-700 text-[11px] font-bold rounded-md">200k</button>
                </div>
            </div>

            <!-- Change Display -->
            <div class="flex justify-between items-center px-3.5 py-2.5 bg-gray-100/80 rounded-xl border border-gray-200">
                <span class="text-xs font-bold text-gray-600">Kembalian</span>
                <span class="text-sm font-extrabold text-emerald-600">
                    Rp {{ number_format($this->change, 0, ',', '.') }}
                </span>
            </div>
        @endif

        <!-- Checkout Button -->
        <button 
            wire:click="checkout" 
            wire:loading.attr="disabled"
            @if(empty($cart)) disabled @endif
            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-extrabold text-sm rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 active:scale-95">
            <span wire:loading.remove>Proses & Bayar</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </div>

</div>
