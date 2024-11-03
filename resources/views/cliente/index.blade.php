@extends('template')

@section('title', 'Clientes')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

@if (session('success'))
<script>
let message = "{{ session('success') }}";
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
Toast.fire({
    icon: "success",
    title: message
});
</script>
@endif
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Clientes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Clientes</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-4"></i>
            Tabla Cliente
        </div>
        <div class="card-body">
            <table id="datatablesSimple" , class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre:</th>
                        <th>Direccion</th>
                        <th>Documento</th>
                        <th>Telefono</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $item)
                    <tr>
                        <td>
                            {{ $item->nombre}}
                        </td>
                        <td>
                            {{ $item->descripcion }}
                        </td>
                        <td>
                        {{ $item->cedula }}
                        </td>
                        <td>
                            {{$item->numero_celular}}
                        </td>
                    </tr>
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