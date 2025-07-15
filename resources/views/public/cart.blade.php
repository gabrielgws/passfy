@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Carrinho de Compras</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Revise seus ingressos antes de finalizar a compra</p>
        </div>

        @livewire('public.cart-page')
    </div>
</div>
@endsection
