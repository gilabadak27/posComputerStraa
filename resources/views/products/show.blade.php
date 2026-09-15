<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')

    <title>{{ $product->name }} - POS-Computer</title>
</head>
<body class="bg-gray-100 text-gray-800">
    @include('layouts.header')

    <main class="min-h-screen container mx-auto px-6 py-12">
        <a href="{{ url('/') }}" class="mb-8 mt-6 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 transition hover:text-indigo-800">
            <span aria-hidden="true">&larr;</span> Kembali ke katalog
        </a>

        <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
            <div class="grid gap-0 lg:grid-cols-2">
                <div class="flex min-h-[360px] items-center justify-center bg-gray-50 p-8 lg:min-h-[520px]">
                    @if ($product->img)
                        <img
                            src="{{ Str::startsWith($product->img, ['http://', 'https://']) ? $product->img : asset('storage/' . $product->img) }}"
                            alt="{{ $product->name }}"
                            class="max-h-[460px] w-full object-contain"
                        >
                    @else
                        <div class="flex flex-col items-center text-gray-300">
                            <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="mt-3 text-sm">Tidak ada gambar</span>
                        </div>
                    @endif
                </div>

                <div class="p-8 lg:p-12">
                    <p class="text-sm font-bold uppercase tracking-wider text-indigo-600">
                        {{ $product->category->name ?? 'Umum' }}
                    </p>
                    <h1 class="mt-3 text-3xl font-extrabold leading-tight text-gray-900 sm:text-4xl">{{ $product->name }}</h1>

                    <div class="mt-6 border-y border-gray-100 py-6">
                        <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">Harga Jual</p>
                        <p class="mt-1 text-3xl font-extrabold text-indigo-600">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</p>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Stok</p>
                            <p class="mt-1 font-bold text-gray-900">{{ $product->stock }} {{ $product->unit ?? 'pcs' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</p>
                            <p class="mt-1 font-bold text-emerald-600">Tersedia</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h2 class="text-lg font-bold text-gray-900">Deskripsi Produk</h2>
                        <p class="mt-3 whitespace-pre-line leading-relaxed text-gray-600">{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
                    </div>
                </div>
            </div>
        </article>
    </main>

    @include('layouts.footer')
</body>
</html>