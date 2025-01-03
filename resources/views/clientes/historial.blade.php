@extends('template')

@section('title', 'Historial de Cliente')

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Historial de {{ $cliente->nombre }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Historial</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-history me-1"></i> Historial de Actividades
        </div>
        <div class="card-body">
            <div class="accordion" id="accordionHistorial">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAlquileres">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAlquileres" aria-expanded="true" aria-controls="collapseAlquileres">
                            Alquileres
                        </button>
                    </h2>
                    <div id="collapseAlquileres" class="accordion-collapse collapse show" aria-labelledby="headingAlquileres" data-bs-parent="#accordionHistorial">
                        <div class="accordion-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Vestido</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->alquileres as $alquiler)
                                        <tr>
                                            <td>{{ $alquiler->vestido->nombre }}</td>
                                            <td>{{ \Carbon\Carbon::parse($alquiler->fecha_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($alquiler->fecha_fin)->format('d/m/Y') }}</td>
                                            <td>{{ $alquiler->estado }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingReservas">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReservas" aria-expanded="false" aria-controls="collapseReservas">
                            Reservas
                        </button>
                    </h2>
                    <div id="collapseReservas" class="accordion-collapse collapse" aria-labelledby="headingReservas" data-bs-parent="#accordionHistorial">
                        <div class="accordion-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Vestido</th>
                                        <th>Fecha Reserva</th>
                                        <th>Fecha Evento</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->reservas as $reserva)
                                        <tr>
                                            <td>{{ $reserva->vestido->nombre }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_evento)->format('d/m/Y') }}</td>
                                            <td>{{ $reserva->estado }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingVentas">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false" aria-controls="collapseVentas">
                            Ventas
                        </button>
                    </h2>
                    <div id="collapseVentas" class="accordion-collapse collapse" aria-labelledby="headingVentas" data-bs-parent="#accordionHistorial">
                        <div class="accordion-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Vestido</th>
                                        <th>Fecha Venta</th>
                                        <th>Precio Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cliente->ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->vestido->nombre }}</td>
                                            <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                                            <td>{{ $venta->precio_total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botón Volver -->
            <div class="text-center mt-4">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
