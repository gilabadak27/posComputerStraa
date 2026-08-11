<div class="space-y-6">
    
    <!-- Top Filter Bar: Search Input & Category Pills -->
    <div class="bg-white/80 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-gray-200/80 shadow-sm space-y-4">
        
        <!-- Search Input -->
        <div class="relative w-full">
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search" 
                placeholder="Cari produk komputer, komponen, aksesoris..." 
                class="w-full pl-11 pr-4 py-2.5 bg-gray-50/80 focus:bg-white rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm font-medium transition-all outline-none"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <button 
                wire:click="selectCategory(null)" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all shadow-sm {{ is_null($selectedCategory) ? 'bg-indigo-600 text-white shadow-indigo-100' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                Semua Kategori
            </button>
            @foreach ($categories as $cat)
                <button 
                    wire:click="selectCategory({{ $cat->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-all shadow-sm {{ $selectedCategory === $cat->id ? 'bg-indigo-600 text-white shadow-indigo-100' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                    {{ $cat->name }} ({{ $cat->products_count }})
                </button>
            @endforeach
        </div>
    </div>

    <!-- BIG Product Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($products as $product)
            <div class="bg-white rounded-2xl border border-gray-200/90 p-5 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all flex flex-col justify-between group">
                
                <div>
                    <!-- Category & Stock Badges -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-bold tracking-wide uppercase px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>

                        @if ($product->stock > 5)
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                Stok: {{ $product->stock }} {{ $product->unit ?? 'pcs' }}
                            </span>
                        @elseif($product->stock > 0)
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full border border-amber-200">
                                Sisa: {{ $product->stock }} {{ $product->unit ?? 'pcs' }}
                            </span>
                        @else
                            <span class="text-[11px] font-bold px-2.5 py-0.5 bg-rose-50 text-rose-600 rounded-full border border-rose-200">
                                Stok Habis
                            </span>
                        @endif
                    </div>

                    <!-- Product Image Display -->
                    <div class="w-full h-40 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden mb-4 group-hover:scale-[1.02] transition-transform duration-200">
                        @if ($product->img)
                            <img src="{{ Str::startsWith($product->img, ['http://', 'https://']) ? $product->img : asset('storage/' . $product->img) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-2" />
                        @else
                            <div class="flex flex-col items-center text-gray-300">
                                <svg class="w-12 h-12 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-[11px] text-gray-400 mt-1">No Image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Title -->
                    <h3 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-snug">
                        {{ $product->name }}
                    </h3>
                    @if ($product->description)
                        <p class="text-xs text-gray-500 line-clamp-1 mt-1 font-normal">{{ $product->description }}</p>
                    @endif
                </div>

                <!-- Price & Add Button -->
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase block">Harga Jual</span>
                        <span class="text-base font-extrabold text-gray-900 tracking-tight">
                            Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <button 
                        wire:click="addToCart({{ $product->id }})" 
                        @if($product->stock <= 0) disabled @endif
                        class="px-3.5 py-2 rounded-xl font-bold text-xs flex items-center gap-1 shadow-sm transition-all active:scale-95 {{ $product->stock > 0 ? 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-indigo-100' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-300 p-8">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h4 class="text-sm font-bold text-gray-700">Produk Tidak Ditemukan</h4>
                <p class="text-xs text-gray-500 mt-1">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
        @endforelse
    </div>
</div>
