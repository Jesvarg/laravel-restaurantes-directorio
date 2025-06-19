@extends('layouts.app')
@section('title', 'Editar Restaurante - ' . $restaurant->name)

@push('styles')
<link href="{{ asset('css/forms.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <div class="card form-card">
        <div class="card-header">
            <h1 class="card-title h4"><i class="bi bi-pencil-square me-2"></i>Editar Restaurante: {{ $restaurant->name }}</h1>
        </div>
        <div class="card-body">
            @include('restaurants.partials._form', ['restaurant' => $restaurant, 'buttonText' => 'Actualizar Restaurante', 'iconClass' => 'bi-arrow-clockwise'])
        </div>
    </div>
</div>
@endsection