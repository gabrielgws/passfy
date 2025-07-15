<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Passfy - Sua Plataforma de Eventos' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Header -->
        <x-public.header />

        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <x-public.footer />
    </div>

    <!-- Carrinho de Compras -->
    @livewire('public.cart')

    @livewireScripts

    <!-- Toast Notifications -->
    <script>
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSuccess', (message) => {
                // Implementar toast de sucesso
                console.log('Success:', message);
            });

            Livewire.on('showError', (message) => {
                // Implementar toast de erro
                console.log('Error:', message);
            });
        });
    </script>
</body>

</html>
