@extends('template')

@section('title', 'Cálculo de Multas')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Cálculo de Multas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('devoluciones.index') }}">Devoluciones</a></li>
        <li class="breadcrumb-item active">Multas</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i> Información de Multa
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $alquiler->cliente->nombre }}</p>
            <p><strong>Vestido:</strong> {{ $alquiler->vestido->nombre }}</p>
            <p><strong>Fecha de Entrega:</strong> {{ $fechaFin }}</p>
            <p><strong>Fecha Actual:</strong> {{ $fechaActual }}</p>
            <p><strong>Días de Retraso:</strong> {{ $diasRetraso > 0 ? $diasRetraso : 'No hay retraso' }}</p>
            <p><strong>Multa Diaria:</strong> {{ number_format($multaDiaria, 0, ',', '.') }} Gs.</p>
            <p><strong>Multa Total Acumulada:</strong> 
                {{ $diasRetraso > 0 ? number_format($multaTotal, 0, ',', '.') . ' Gs.' : 'Sin multa' }}
            </p>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('devoluciones.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
