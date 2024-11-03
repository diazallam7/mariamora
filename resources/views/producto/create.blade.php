@extends('template')

@section('title', 'Crear productos')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Producto</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Producto</a></li>
            <li class="breadcrumb-item active">Crear Producto</li>
        </ol>
        <div class="contriner w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">

                    <div class="col-md-4 mb-2">
                        <label for="nombre" class="form-label">Nombre y Apellido:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre') }}">
                        @error('nombre')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="descripcion" class="form-label">Direccion:</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control"
                        value="{{ old('descripcion') }}">
                        @error('descripcion')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="cedula" class="form-label">Cedula:</label>
                        <input type="text" name="cedula" id="cedula" class="form-control"
                            value="{{ old('cedula') }}">
                        @error('cedula')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="numero_celular" class="form-label">Telefono:</label>
                        <input type="text" name="numero_celular" id="numero_celular" class="form-control"
                            value="{{ old('numero_celular') }}">
                        @error('numero_celular')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="codigo" class="form-label">Nro de Boleta:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control"
                            value="{{ old('codigo') }}">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="nombre_del_producto" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre_del_producto" id="nombre_del_producto" class="form-control"
                            value="{{ old('nombre_del_producto') }}">
                        @error('nombre_del_producto')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="precio_compra" class="form-label">Total:</label>
                        <input type="text" name="precio_compra" id="precio_compra" class="form-control"
                            value="{{ old('precio_compra') }}">
                        @error('precio_compra')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Entrega:</label>
                        <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                            value="{{ old('fecha_vencimiento') }}">
                        @error('fecha_vencimiento')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="sumbit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
    document.getElementById('precio_compra').addEventListener('input', function (e) {
        // Mantiene el valor con puntos solo en la interfaz de usuario
        let value = e.target.value.replace(/\D/g, '');
        value = new Intl.NumberFormat('es-ES').format(value);
        e.target.value = value;
    });

    document.getElementById('precio_compra').form.addEventListener('submit', function () {
        // Elimina los puntos antes de enviar el formulario para que se guarde correctamente
        let input = document.getElementById('precio_compra');
        input.value = input.value.replace(/\./g, '');
    });
</script>
    <script>
document.getElementById('cedula').addEventListener('change', function() {
    var cedula = this.value;

    if (cedula) {
        fetch('/buscar-persona?cedula=' + cedula)
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('No se encontró ninguna persona con esa cédula.');
                }
            })
            .then(data => {
                // Si la persona se encontró, llenar los campos
                document.getElementById('nombre').value = data.nombre || '';
                document.getElementById('descripcion').value = data.descripcion || '';
                document.getElementById('numero_celular').value = data.numero_celular || '';
            })
            .catch(error => {
                // Mostrar un mensaje de alerta si no se encontró la cédula
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });

                // Limpiar los campos
                document.getElementById('nombre').value = '';
                document.getElementById('descripcion').value = '';
                document.getElementById('numero_celular').value = '';
            });
    }
});

document.getElementById('nombre').addEventListener('change', function() {
    var nombre = this.value;

    if (nombre) {
        fetch('/buscar-por-nombre?nombre=' + nombre)
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('No se encontró ninguna persona con ese nombre.');
                }
            })
            .then(data => {
                // Si la persona se encontró, llenar los campos
                document.getElementById('cedula').value = data.cedula || '';
                document.getElementById('descripcion').value = data.descripcion || '';
                document.getElementById('numero_celular').value = data.numero_celular || '';
            })
            .catch(error => {
                // Mostrar un mensaje de alerta si no se encontró el nombre
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });

                // Limpiar los campos
                document.getElementById('cedula').value = '';
                document.getElementById('descripcion').value = '';
                document.getElementById('numero_celular').value = '';
            });
    }
});

    </script>
@endpush
