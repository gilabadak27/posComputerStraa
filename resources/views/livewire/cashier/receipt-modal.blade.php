<div>
    @if ($showModal && $receiptData)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl max-w-sm w-full p-6 space-y-4 animate-in fade-in zoom-in duration-150">
                
                <!-- Receipt Header -->
                <div class="text-center pb-3 border-b border-dashed border-gray-300">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl mx-auto flex items-center justify-center mb-2 font-black">POS</div>
                    <h3 class="text-base font-bold text-gray-900">TOKO COMPUTER STRAA</h3>
                    <p class="text-[11px] text-gray-500">Jl. Komputer No. 27 • Telp: (021) 555-0199</p>
                    <p class="text-[11px] font-mono text-indigo-600 mt-1 font-bold">{{ $receiptData['invoice_number'] }}</p>
                </div>

                <!-- Meta Info -->
                <div class="text-xs space-y-1 font-mono text-gray-600">
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span>{{ $receiptData['created_at'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kasir:</span>
                        <span>{{ $receiptData['cashier_name'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Metode:</span>
                        <span>{{ $receiptData['payment_method'] }}</span>
                    </div>
                </div>

                <!-- Itemized Breakdown -->
                <div class="border-t border-b border-dashed border-gray-300 py-3 space-y-2 max-h-48 overflow-y-auto font-mono text-xs">
                    @foreach ($receiptData['items'] as $item)
                        <div class="flex justify-between">
                            <div class="pr-2">
                                <p class="font-bold text-gray-900 line-clamp-1">{{ $item['name'] }}</p>
                                <p class="text-[10px] text-gray-500">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-gray-800 shrink-0">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Receipt Totals -->
                <div class="font-mono text-xs space-y-1.5 pt-1">
                    <div class="flex justify-between text-gray-900 font-bold">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($receiptData['total_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Bayar</span>
                        <span>Rp {{ number_format($receiptData['paid_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Kembali</span>
                        <span>Rp {{ number_format($receiptData['change_amount'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 border-t border-gray-100 flex gap-2">
                    <button 
                        onclick="window.print()" 
                        class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Struk
                    </button>
                    <button 
                        wire:click="closeModal" 
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
