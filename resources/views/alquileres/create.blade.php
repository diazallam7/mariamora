@extends('template')

@section('title', 'Nuevo Alquiler')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Registrar Nuevo Alquiler</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('alquileres.index') }}">Alquileres</a></li>
            <li class="breadcrumb-item active">Nuevo Alquiler</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus-circle me-2"></i>Formulario de Registro
            </div>
            <div class="card-body">
                <form action="{{ route('alquileres.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select @error('cliente_id') is-invalid @enderror" name="cliente_id"
                                id="cliente_id" required>
                                <option value="" selected disabled>Seleccione un cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vestido_id" class="form-label">Vestido</label>
                            <select class="form-select @error('vestido_id') is-invalid @enderror" id="vestido_id"
                                name="vestido_id" onchange="updatePrecio()" required>
                                <option value="" selected disabled>Seleccione un vestido</option>
                                @foreach ($vestidos as $vestido)
                                    <option value="{{ $vestido->id }}" data-precio="{{ $vestido->precio_alquiler }}"
                                        {{ old('vestido_id') == $vestido->id ? 'selected' : '' }}>
                                        {{ $vestido->nombre }} ({{ $vestido->categoria }}, {{ $vestido->color }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vestido_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="text" class="form-control @error('fecha_inicio') is-invalid @enderror"
                                name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                placeholder="Seleccione una fecha" required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="text" class="form-control @error('fecha_fin') is-invalid @enderror"
                                name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                                placeholder="Seleccione una fecha" required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="costo_total" class="form-label">Costo Total (₲)</label>
                        <input type="number" step="0.01" class="form-control @error('costo_total') is-invalid @enderror"
                            name="costo_total" id="costo_total" value="{{ old('costo_total') }}"
                            placeholder="Ingrese el costo total" required>
                        @error('costo_total')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="estado" value="activo">

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Registrar Alquiler</button>
                        <a href="{{ route('alquileres.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePrecio() {
            const vestidoSelect = document.getElementById('vestido_id');
            const costoTotalInput = document.getElementById('costo_total');
            const selectedOption = vestidoSelect.options[vestidoSelect.selectedIndex];

            if (selectedOption && selectedOption.dataset.precio) {
                costoTotalInput.value = selectedOption.dataset.precio;
            }
        }
    </script>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#fecha_inicio", {
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "es"
        });
        flatpickr("#fecha_fin", {
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "es"
        });
    </script>
@endpush
