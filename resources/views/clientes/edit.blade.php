@extends('template')

@section('title', 'Editar Cliente')

@push('css')
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Editar Cliente</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Cliente</a></li>
            <li class="breadcrumb-item active">Editar Cliente</li>
        </ol>

        <div class="contriner w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('clientes.update',['cliente'=>$cliente]) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tipo_persona" class="form-label">Tipo de Persona: <span class="fw-bold">{{strtoupper($cliente->persona->tipo_persona)}}</span></label>
                    </div>

                    <div class="col-md-12 mb-2" id="box-razon-social">
                        @if ($cliente->persona->razon_social == 'natural')
                        <label id="label-natural" for="razon_social" class="form-label">Nombre de la Empresa</label>  
                        @else
                        <label id="label-juridica" for="razon_social" class="form-label">Nombres y Apellidos</label> 
                        @endif

                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social',$cliente->persona->razon_social)}}">

                        @error('razon_social')
                            <small class="text-danger">{{'*'.$message}}</small>
                        @enderror

                    </div>

                    <div class="col-md-12 mb-2">
                        <label for="direccion" class="form-label">Direccion:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion',$cliente->persona->direccion)}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de Documento:</label>
                        <select class="form-select" name="documento_id" id="docuemnto_id">

                            @foreach ($documentos as $item)
                            @if ($cliente->persona->documento->id == $item->id)
                            <option selected value="{{$item->id}}" {{ old('docuemnto_id')== $item->id ? 'selected' : ''}}>{{$item->tipo_documento}}</option>
                            @else
                            <option value="{{$item->id}}" {{ old('docuemnto_id')== $item->id ? 'selected' : ''}}>{{$item->tipo_documento}}</option>
                            @endif

                            @endforeach
                        </select>

                            @error('documento_id')
                            <small class="text-danger">{{'*'.$message }}</small>
                            @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="numero_documento" class="form-label">Numero de Documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento',$cliente->persona->numero_documento)}}">
                        @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
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
@endpush
