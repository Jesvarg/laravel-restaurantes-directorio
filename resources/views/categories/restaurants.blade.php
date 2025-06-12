@extends('layouts.app')
@section('title', 'Restaurantes en ' . $category->name)

@section('content')
<h2>Restaurantes en {{ $category->name }}</h2>

@if($restaurants->count() > 0)
    <div class="row">
        @foreach ($restaurants as $restaurant)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($restaurant->photos->count() > 0)
                        <img src="{{ $restaurant->photos->first()->url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text">{{ $restaurant->address }}</p>
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-sm btn-info">Ver detalles</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $restaurants->links() }}
    </div>
@else
    <div class="alert alert-info">
        No hay restaurantes en esta categoría.
    </div>
@endif

<div class="mt-3">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Volver a categorías</a>
</div>
@endsection