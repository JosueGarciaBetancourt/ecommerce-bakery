@extends('layouts.plantilla')

@section('title', 'Mis pedidos')
@section('titulo', 'Historial de pedidos')

@push('styles')
    @vite(['resources/css/pedidosIndex.css']) {{-- crea este CSS si quieres --}}
@endpush

@section('content')
<section class="contenido">
    <div class="columna">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse ($pedidos as $pedido)
            <div class="card-pedido">
                <h5>Pedido #{{ $pedido->id }}</h5>
                <p><strong>Total:</strong> S/. {{ number_format($pedido->total, 2) }}</p>
                <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn">Ver Detalles</a>
            </div>
        @empty
            <div class="no-pedidos">
                <p>No has realizado ningún pedido aún.</p>
            </div>
        @endforelse
    </div>
</section>

@endsection
