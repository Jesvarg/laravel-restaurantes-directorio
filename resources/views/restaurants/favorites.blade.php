@extends('layouts.app')
@section('title', 'Mis Restaurantes Favoritos')

@section('content')
<h2>Mis Restaurantes Favoritos</h2>

@if($favorites->count() > 0)
    <div class="row">
        @foreach ($favorites as $restaurant)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($restaurant->photos->count() > 0)
                        <img src="{{ $restaurant->photos->first()->url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text">{{ $restaurant->address }}</p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-info">Ver</a>
                            <form action="{{ route('restaurants.unfavorite', $restaurant) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Quitar de favoritos</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        No tienes restaurantes favoritos. Explora nuestros <a href="{{ route('restaurants.index') }}">restaurantes</a> y añade algunos a tus favoritos.
    </div>
@endif

<a href="{{ route('restaurants.index') }}" class="btn btn-secondary mt-3">Volver a restaurantes</a>
@endsection