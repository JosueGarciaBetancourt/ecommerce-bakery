@extends('layouts.plantilla')

@section('title', 'Home')

@section('titulo', 'Pastelería')
@push('styles')
    @vite(['resources/css/home.css'])
@endpush


@section('content')
    {{-- Script para carrusel --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Cargar Ventana Agregar Reseña --}}
    <div id="modalCrearReseña" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalBody">
                @include('Opiniones.opinionCreate') <!-- Carga la vista directamente -->
            </div>
        </div>
    </div>

    <section class="contenido">

        <div class="nosotros">
            <div class="overlayNosotros">
                <div class="contentNosotros">
                    <h1>SOBRE NOSOTROS</h1>
                    <p>
                      En Pan Comido convertimos la pastelería en arte, combinando tradición, creatividad y los mejores ingredientes. Cada postre es elaborado con técnicas artesanales y atención al detalle, ofreciendo tortas elegantes, macarons, tartaletas y pasteles personalizados que hacen de cada momento algo memorable.
                    </p>
                </div>
            </div>
        </div>

        <div class="container">

          <!-- Sección de Opiniones -->
          <div class="contenido-opiniones">
            <h1 class="titulo-opiniones">Opiniones</h1>
          
            <!-- Contenedor del Swiper -->
            <div class="swiper">
              <!-- Contenedor de las slides -->
              <div class="swiper-wrapper">
                @foreach ($opiniones as $opinion)
                  <div class="swiper-slide">
                    <div class="task">
                      <div class="encabezado">
                        <div class="usuario">
                          <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="usuario"/>
                          <span>{{ $opinion->user->name ?? 'Usuario anónimo' }}</span>
                        </div>
                        <div class="fecha">{{ $opinion->created_at ? $opinion->created_at->format('d M Y') : 'Fecha no disponible' }}</div>
                      </div>
                      <div class="estrellas">
                        @for ($i = 0; $i < $opinion->estrellas; $i++)
                          ★
                        @endfor
                      </div>
                      <p>{{ $opinion->opiniontext }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
          
              <!-- Botones de navegación -->
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>
            </div>
          
            <button class="button-rojo" id="openModal">Agregar Reseña</button>

          </div>
          

            <!-- Formulario para enviar mensaje -->
            <h2 class="form-titulo">Enviar mensaje</h2>
            <form class="formulario">
              <div class="form-container">
                <div class="mensaje">
                  <label for="mensaje">Mensaje</label>
                  <textarea id="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
                </div>
                <div class="datos">
                  <label for="nombre">Tu nombre</label>
                  <input type="text" id="nombre" placeholder="Tu nombre" required />
                  
                  <label for="correo">Tu correo</label>
                  <input type="email" id="correo" placeholder="Tu correo" required />
                  
                  <label for="telefono">Tu teléfono</label>
                  <input type="tel" id="telefono" placeholder="Tu teléfono" required />
                </div>
              </div>
                <button type="button" id="btnEnviar" class="button-rojo">Enviar</button>
            </form>
          </div>

          <!-- Sección de Mapa -->
          <div class="mapa">
            <h2>Ubicación</h2>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0933024023593!2d-122.42177868468116!3d37.77492977975924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085815046dd0d87%3A0xd54152f05b99e853!2sPanadería%20Ejemplo!5e0!3m2!1ses-419!2scl!4v1618302733962!5m2!1ses-419!2scl"
              width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>

          </div>


    </section>

    <script>
      const swiper = new Swiper('.swiper', {
        slidesPerView: 3, // Mostrar 3 opiniones a la vez
        spaceBetween: 30, // Espacio entre ellas
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        loop: true, // Hace que el carrusel sea infinito
        breakpoints: {
          0: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 }
        }
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
          const modal = document.getElementById("modalCrearReseña");
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
