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
    </main>

    @include('layouts.footer')
</body>
</html>