@extends('template')

@section('title', 'Alquileres')

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
    <h1 class="mt-4 text-center">Alquileres</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Alquileres</li>
    </ol>
    <div class="mb-4">
        <a href="{{ route('alquileres.create') }}" class="btn btn-primary">Nuevo Alquiler</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-2"></i> Listado de Alquileres
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Vestido</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Días de Alquiler</th>
                        <th>Costo Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alquileres as $alquiler)
                        <tr>
                            <td>{{ $alquiler->cliente->nombre }}</td>
                            <td>{{ $alquiler->vestido->nombre }}</td>
                            <td>{{ $alquiler->fecha_inicio->format('d/m/Y') }}</td>
                            <td>{{ $alquiler->fecha_fin->format('d/m/Y') }}</td>
                            <td>{{ $alquiler->dias }}</td>
                            <td>₲ {{ number_format($alquiler->costo_total, 0, ',', '.') }}</td>
                            <td>
                                @if ($alquiler->estado === 1)
                                    <span class="badge bg-primary">Activo</span>
                                @elseif ($alquiler->estado === 2)
                                    <span class="badge bg-warning text-dark">Completado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic actions">

                                    <!-- Botón para marcar como devuelto -->
                                    @if ($alquiler->estado == 'activo')
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#devolverModal-{{ $alquiler->id }}">Devolver</button>
                                    @endif

                                    <!-- Botón para eliminar -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#eliminarModal-{{ $alquiler->id }}">Eliminar</button>
                                    </div><a href="{{ route('factura.alquiler', $alquiler->id) }}" class="btn btn-primary">Comprobante</a>

                                </div>
                            </td>
                        </tr>

                        <!-- Modal para marcar como devuelto -->
                        <div class="modal fade" id="devolverModal-{{ $alquiler->id }}" tabindex="-1"
                            aria-labelledby="devolverModalLabel-{{ $alquiler->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="devolverModalLabel-{{ $alquiler->id }}">Marcar como Devuelto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas marcar este alquiler como devuelto?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="{{ route('alquileres.alquileres.devolver', ['alquiler' => $alquiler->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success">Devolver</button>
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para eliminar -->
                        <div class="modal fade" id="eliminarModal-{{ $alquiler->id }}" tabindex="-1"
                            aria-labelledby="eliminarModalLabel-{{ $alquiler->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="eliminarModalLabel-{{ $alquiler->id }}">Eliminar Alquiler</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar este alquiler? Esta acción no se puede deshacer.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form action="{{ route('alquileres.destroy', ['alquiler' => $alquiler->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
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
