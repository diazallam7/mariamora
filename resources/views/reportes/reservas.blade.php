@extends('template')

@section('title', 'Reporte de Reservas')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Reporte de Reservas</h1>
    <form method="GET" action="{{ route('reportes.reservas') }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha de Reserva:</label>
                <input type="date" name="fecha" class="form-control" value="{{ $fecha ?? '' }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Detalles de Reservas
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Vestido</th>
                        <th>Fecha de Reserva</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->cliente->nombre }}</td>
                            <td>{{ $reserva->vestido->nombre }}</td>
                            <td>{{ $reserva->fecha_reserva }}</td>
                            <td>{{ ucfirst($reserva->estado) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
