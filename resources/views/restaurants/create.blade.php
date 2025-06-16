@extends('layouts.app')
@section('title', 'Añadir Nuevo Restaurante')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm form-card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-plus-circle-fill me-2"></i>Añadir Nuevo Restaurante</h4>
                </div>
                <div class="card-body">
                    @include('restaurants.partials._form', ['restaurant' => new App\Models\Restaurant(), 'buttonText' => 'Crear Restaurante', 'iconClass' => 'bi-check-circle-fill'])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection