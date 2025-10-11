@push('styles')
    @vite(['resources/css/createOpinion.css'])
@endpush

<h2 class="form-title"> Agregar Reseña </h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form action="{{ route('opiniones.store') }}" method="POST" enctype="multipart/form-data" class="formCrearOpinion">
    @csrf

    <div class="formCrearOpinion-Container">

        {{-- Campo de texto de opinión --}}
        <div class="form-group">
            <textarea name="opiniontext" class="form-control" placeholder="Descripción" rows="5">{{ old('opiniontext') }}</textarea>
            @error('opiniontext')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Calificación por estrellas --}}
        <div class="form-group">
            <div class="rating">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="estrellas" value="{{ $i }}" {{ old('estrellas') == $i ? 'checked' : '' }}/>
                    <label for="star{{ $i }}" title="{{ $i }} estrellas"></label>
                @endfor
            </div>
            @error('estrellas')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Botón de envío --}}
        <div class="form-group">
            <button type="submit" class="btn submit-btn">Enviar Formulario</button>
        </div>

    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.formCrearOpinion');
    const textarea = form.querySelector('textarea[name="opiniontext"]');
    const estrellasInputs = form.querySelectorAll('input[name="estrellas"]');

    form.addEventListener('submit', function (e) {
        let isValid = true;
        let errors = [];

        // Limpiar errores anteriores
        const oldErrors = form.querySelectorAll('.text-danger');
        oldErrors.forEach(el => el.remove());

        // Validar texto
        const text = textarea.value.trim();
        if (text.length < 10) {
            isValid = false;
            errors.push({ field: textarea, message: "La descripción debe tener al menos 10 caracteres." });
        }

        // Validar estrellas
        let estrellaSeleccionada = false;
        estrellasInputs.forEach(input => {
            if (input.checked) estrellaSeleccionada = true;
        });
        if (!estrellaSeleccionada) {
            isValid = false;
            const ratingDiv = form.querySelector('.rating');
            errors.push({ field: ratingDiv, message: "Debe seleccionar una calificación." });
        }

        // Si hay errores, evitamos el envío
        if (!isValid) {
            e.preventDefault();
            errors.forEach(err => {
                const span = document.createElement('span');
                span.classList.add('text-danger');
                span.innerText = err.message;
                err.field.insertAdjacentElement('afterend', span);
            });
        }
    });
});
</script>
