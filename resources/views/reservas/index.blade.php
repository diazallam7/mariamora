@extends('template')

@section('title', 'Reservas')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
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
        <h1 class="mt-4 text-center">Reservas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Reservas</li>
        </ol>
        <div class="mb-4">
            <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nueva Reserva</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-2"></i> Listado de Reservas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Vestido</th>
                            <th>Fecha de Reserva</th>
                            <th>Fecha de Evento</th>
                            <th>Anticipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->cliente->nombre }}</td>
                                <td>{{ $reserva->vestido->nombre }}</td>
                                <td>{{ $reserva->fecha_reserva->format('d/m/Y') }}</td>
                                <td>{{ $reserva->fecha_evento->format('d/m/Y') }}</td>
                                <td>₲ {{ number_format($reserva->anticipo, 0, ',', '.') }}</td>
                                <td>
                                    @if ($reserva->estado === 1)
                                        <span class="badge bg-primary">Activo</span>
                                    @elseif ($reserva->estado === 2)
                                        <span class="badge bg-warning text-dark">Completado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic actions">
                                        @if ($reserva->estado === 1)
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#alquilarModal-{{ $reserva->id }}">Alquilar</button>
                                        @endif
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#eliminarModal-{{ $reserva->id }}">Eliminar</button>

                                        <a href="{{ route('factura.reserva', $reserva->id) }}"
                                            class="btn btn-primary">Comprobante</a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal para alquilar -->
                            <div class="modal fade" id="alquilarModal-{{ $reserva->id }}" tabindex="-1"
                                aria-labelledby="alquilarModalLabel-{{ $reserva->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="alquilarModalLabel-{{ $reserva->id }}">Confirmar
                                                Alquiler</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('reservas.reservas.alquilar', $reserva->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="fecha_inicio-{{ $reserva->id }}" class="form-label">Fecha
                                                        de Inicio</label>
                                                    <input type="date" class="form-control"
                                                        id="fecha_inicio-{{ $reserva->id }}" name="fecha_inicio" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fecha_fin-{{ $reserva->id }}" class="form-label">Fecha de
                                                        Fin</label>
                                                    <input type="date" class="form-control"
                                                        id="fecha_fin-{{ $reserva->id }}" name="fecha_fin" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success">Confirmar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para eliminar -->
                            <div class="modal fade" id="eliminarModal-{{ $reserva->id }}" tabindex="-1"
                                aria-labelledby="eliminarModalLabel-{{ $reserva->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="eliminarModalLabel-{{ $reserva->id }}">Eliminar
                                                Reserva</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que deseas eliminar esta reserva permanentemente? Esta
                                                acción no se puede deshacer.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No,
                                                cancelar</button>
                                            <form action="{{ route('reservas.destroy', ['reserva' => $reserva->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
