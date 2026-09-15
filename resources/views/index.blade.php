<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <title>POS-Computer</title>
</head>
<body class="bg-gray-100 text-gray-800">
    @include('layouts.header')

    <main class="min-h-screen container mx-auto px-6 py-12">
        <section class="text-center py-16">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang di POS-Computer</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Sistem point of sale yang cepat, modern, dan siap membantu operasional bisnis Anda.
            </p>
        </section>

        <section class="pb-16">
            <div class="flex items-end justify-between gap-4 mb-6">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-blue-300">Katalog Produk</p>
                    <h2 class="text-2xl font-bold text-gray-900 mt-1">Produk Terbaru</h2>
                </div>
                <span class="text-sm text-gray-500">{{ $products->count() }} produk tersedia</span>
            </div>

            <form method="GET" action="{{ url('/') }}" class="mb-6 flex flex-col gap-3 sm:flex-row">
                <label for="product-search" class="sr-only">Cari produk</label>
                <input
                    id="product-search"
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama atau deskripsi produk..."
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                <button type="submit" class="rounded-xl bg-blue-300 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-400/50">
                    Cari Produk
                </button>
                @if ($search)
                    <a href="{{ url('/') }}" class="rounded-xl border border-gray-200 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>

            @if ($products->isNotEmpty())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($products as $product)
                        <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex h-48 items-center justify-center bg-gray-50 p-4">
                                @if ($product->img)
                                    <img
                                        src="{{ Str::startsWith($product->img, ['http://', 'https://']) ? $product->img : asset('storage/' . $product->img) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-contain"
                                    >
                                @else
                                    <svg class="h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-300">
                                    {{ $product->category->name ?? 'Umum' }}
                                </p>
                                <h3 class="mt-2 line-clamp-2 min-h-12 text-lg font-bold text-gray-900">{{ $product->name }}</h3>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <span class="text-lg font-extrabold text-gray-900">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-400/50">
                                        Stok {{ $product->stock }} {{ $product->unit ?? 'pcs' }}
                                    </span>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="mt-5 block rounded-xl bg-blue-300 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-blue-400/50">
                                    Lihat Detail Produk
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center text-gray-500">
                    Belum ada produk aktif.
                </div>
            @endif
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>