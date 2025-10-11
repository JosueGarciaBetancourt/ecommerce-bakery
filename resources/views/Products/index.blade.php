@extends('layouts.plantilla')

@section('title', 'Productos')

@section('titulo', 'Productos')

@push('styles')
    @vite(['resources/css/indexProduct.css', 'resources/css/createProduct.css'])
@endpush

@section('content')

    <section class="admin">
        @can('productos.create')
            <button class="btn" id="openModal">Crear Producto</button>
        @endcan

        <div id="modalCrearProducto" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modalBody">
                    @include('Products.create') <!-- Carga la vista directamente -->
                </div>
            </div>
        </div>
    </section>

    <section class="filters">
        
        <div class="search-box">
            <form method="GET" action="{{ route('productos.index') }}">
                <input type="text" name="search" placeholder="Buscar producto..." value="{{ request('search') }}">
            </form>
        </div>
        
        <div class="filter-selects">
            <form method="GET" action="{{ route('productos.index') }}">
                <select id="categoryFilter" name="category" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria }}" {{ request('category') == $categoria ? 'selected' : '' }}>
                            {{ $categoria }}
                        </option>
                    @endforeach
                </select>

            
                          
                <select id="sortFilter" name="sort" onchange="this.form.submit()">
                    <option value="">Ordenar por</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                </select>
            </form>
        </div>

    </section>

    <section class="products">

        <div class="grid-container">
            @foreach ($productos as $producto)

                <div class="cardproduct">
                    <a href="{{route('productos.show', $producto->id)}}">
                        <div class="img">
                            <img src="{{ asset($producto->file_uri) }}">
                        </div>
                    
                        <div class="text">
                            <h3 class="h3">{{$producto->name}}</h3>
                            <p class="p"> S/. {{$producto->price}} </p>
                        </div>
                    </a>
                </div>

            @endforeach
        </div>

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("modalCrearProducto");
            const closeModal = document.querySelector(".close");
            const btnOpenModal = document.getElementById("openModal");

            // Asegurar que el modal esté oculto al cargar la página
            modal.classList.remove("show");

            if (!btnOpenModal) return;

            btnOpenModal.addEventListener("click", function (event) {
                event.preventDefault();
                sessionStorage.setItem("modalAbierto", "true");
                modal.classList.add("show"); // Mostrar el modal directamente
            });

            closeModal.addEventListener("click", function () {
                modal.classList.remove("show"); // Oculta el modal
                sessionStorage.removeItem("modalAbierto");
            });

            window.addEventListener("click", function (event) {
                if (event.target === modal) {
                    modal.classList.remove("show");
                    sessionStorage.removeItem("modalAbierto");
                }
            });
        });
    </script>     
    
@endsection
