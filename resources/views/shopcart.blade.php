@extends('layouts.plantilla')

@section('title', 'Carrito de Compra')

@section('titulo', 'CARRITO DE COMPRA')

@push('styles')
    @vite(['resources/css/shopcart.css'])
@endpush

@section('content')
<section class="contenido">
    <div class="columna">
        <h3 class="titulo-caja">Carrito de Compras</h3>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        
            @if($cartItems->isEmpty())
                <div class="cart-item">  
                    <p>No hay productos en el carrito.</p>
                </div>
                
            @else
            
            @foreach($cartItems as $item)
            <div class="cart-item" data-id="{{ $item->id }}">
                <div class="one-row">
                    <img src="{{ asset($item->producto->file_uri) }}" width="50">
                    <div class="details">
                        <h4>{{ $item->producto->name }}</h4>
                        <p>{{ $item->producto->description }}</p>
                    </div>
                </div>

                <div class="second-row">
                    <div class="price">S/.{{ $item->producto->price }}</div>
                    <div class="quantity">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                            @csrf
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit" class="quantity-btn">-</button>
                        </form>
                        <span class="quantity-text" data-id="{{ $item->id }}">{{ $item->cantidad }}</span>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                            @csrf
                            <input type="hidden" name="action" value="increase">
                            <button type="submit" class="quantity-btn">+</button>
                        </form>
                    </div>
                    <div class="total">Total: S/. <span class="total-price" data-id="{{ $item->id }}">{{ $item->producto->price * $item->cantidad }}</span></div>
                    <div class="delete">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="background: none; border: none; padding: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" viewBox="0 0 16 16">
                                    <path d="M6.5 1a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1h3a.5.5 0 0 1 0 1h-10a.5.5 0 0 1 0-1h3V1zM2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V4zm3 2a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zm3 0a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6zm3 0a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        
            @endif

    </div>
    
    <div class="columna">
        <h3 class="titulo-caja">Resumen de Compra</h3>
        <div class="caja2">
            <h1>Total</h1>
            <p><strong>S/.{{ $cartItems->sum(fn($item) => $item->producto->price * $item->cantidad) }}</strong></p>   
            <button type="submit" id="btn_pagar">Proceder al Pago</button>
            
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                    <button type="submit" id="btn_simularPago">Simular Pago</button>    
            </form>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="modalConfirmacion" class="modal-overlay">
        <div class="modal-content">
            <div class="checkmark-circle">
                <div class="background"></div>
                <div class="checkmark draw"></div>
            </div>
            <h2>¡Pedido Realizado!</h2>
            <p>Gracias por tu compra.</p>
            <a href="{{ route('pedidos.index') }}" class="btn-modal">Ver mis pedidos</a>
        </div>
    </div>


    <script src="https://checkout.culqi.com/js/v4"></script>
    <script>
        Culqi.publicKey = 'pk_test_tuLlavePublica'; // Reemplaza con tu llave real

        const totalCarrito = {{ $cartItems->sum(fn($item) => $item->producto->price * $item->cantidad) }};
        const amountEnCentavos = Math.round(totalCarrito * 100); // Culqi usa centavos

        Culqi.settings({
            title: 'Panadería "PAN COMIDO"',
            currency: 'PEN',  // Este parámetro es requerido para realizar pagos yape
            amount: amountEnCentavos,  // Este parámetro es requerido para realizar pagos yape
            order: 'ord_live_0CjjdWhFpEAZlxlz', // Este parámetro es requerido para realizar pagos con pagoEfectivo, billeteras y Cuotéalo
            xculqirsaid: 'Inserta aquí el id de tu llave pública RSA',
            rsapublickey: 'Inserta aquí tu llave pública RSA',
        });
        
        Culqi.options({
            style: {
                logo: "{{ asset('images/LogoBlack.png') }}",
                bannerColor: '#f1396d', // hexadecimal
                buttonBackground: '', // hexadecimal
                menuColor: '', // hexadecimal
                linksColor: '', // hexadecimal
                buttonText: '', // texto que tomará el botón
                buttonTextColor: '', // hexadecimal
                priceColor: '' // hexadecimal
            }
        });

        document.getElementById('btn_pagar').addEventListener('click', function (e) {
            Culqi.open();
            e.preventDefault();
        });

        // Función para recibir respuesta después del pago (opcional)
        function culqi() {
            if (Culqi.token) {
                const token = Culqi.token.id;
                console.log('Token generado: ' + token);
            } else {
                console.error(Culqi.error.user_message);
            }
        }
    </script>

    {{-- Animación del modal de confirmación de pago --}}
    <script>
    document.getElementById('btn_simularPago').addEventListener('click', function(e) {
        // e.preventDefault(); // Evita que se envíe el formulario

        // Mostrar modal
        document.getElementById('modalConfirmacion').style.display = 'flex';

        /* // Simular redirección después de unos segundos
        setTimeout(() => {
            document.querySelector('form[action="{{ route('checkout') }}"]').submit();
        }, 5000); // 5 segundos antes de hacer checkout */
    });
    </script>

    
</section>  

@endsection
