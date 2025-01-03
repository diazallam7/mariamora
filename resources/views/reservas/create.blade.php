@extends('template')

@section('title', 'Crear Reserva')

@push('css')
    <style>
        #observaciones {
            resize: none;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Reserva</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Reservas</a></li>
            <li class="breadcrumb-item active">Crear Reserva</li>
        </ol>
        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('reservas.store') }}" method="post">
                @csrf
                <div class="row g-3">

                    <div class="col-md-6 mb-2">
                        <label for="cliente_id" class="form-label">Cliente:</label>
                        <select name="cliente_id" id="cliente_id" class="form-control">
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="vestido_id" class="form-label">Vestido:</label>
                        <select name="vestido_id" id="vestido_id" class="form-control">
                            <option value="">Seleccione un vestido</option>
                            @foreach ($vestidos as $vestido)
                                <option value="{{ $vestido->id }}" {{ old('vestido_id') == $vestido->id ? 'selected' : '' }}>
                                    {{ $vestido->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('vestido_id')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="fecha_reserva" class="form-label">Fecha de Reserva:</label>
                        <input type="date" name="fecha_reserva" id="fecha_reserva" class="form-control" value="{{ old('fecha_reserva') }}">
                        @error('fecha_reserva')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="fecha_evento" class="form-label">Fecha del Evento:</label>
                        <input type="date" name="fecha_evento" id="fecha_evento" class="form-control" value="{{ old('fecha_evento') }}">
                        @error('fecha_evento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Aquí puedes agregar validaciones adicionales o scripts si es necesario
    </script>
@endpush
