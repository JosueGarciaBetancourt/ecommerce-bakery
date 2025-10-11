@extends('layouts.plantilla')

@section('title', 'Detalle del Pedido')
@section('titulo', 'Pedido #' . $pedido->id)

@push('styles')
    @vite(['resources/css/pedidosShow.css'])
@endpush

@section('content')
<section class="contenido">
    <div class="columna">
        <div class="caja2 mb-4">
            <h4>Información del Pedido</h4>
            <p><strong>Cliente:</strong> {{ $pedido->user ? $pedido->user->name : 'Invitado' }}</p>
            <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total:</strong> S/. {{ number_format($pedido->total, 2) }}</p>
        </div>

        <div class="caja2">
            <h4>Productos</h4>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedido->items as $item)
                        <tr>
                            <td>{{ $item->producto->name }}</td>
                            <td>S/. {{ number_format($item->precio_unitario, 2) }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>S/. {{ number_format($item->cantidad * $item->precio_unitario, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-4">← Volver al historial</a>
    </div>
</section>
@endsection
