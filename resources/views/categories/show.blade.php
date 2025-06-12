@extends('layouts.app')
@section('title', $category->name)

@section('content')
<h2>{{ $category->name }}</h2>

<div class="mb-4">
    <a href="{{ route('categories.restaurants', $category) }}" class="btn btn-primary">Ver restaurantes</a>
    
    @can('update', $category)
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Editar</a>
    @endcan
    
    @can('delete', $category)
        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
        </form>
    @endcan
</div>

<h4>Restaurantes en esta categoría ({{ $category->restaurants->count() }})</h4>

@if($category->restaurants->count() > 0)
    <div class="list-group">
        @foreach($category->restaurants as $restaurant)
            <a href="{{ route('restaurants.show', $restaurant) }}" class="list-group-item list-group-item-action">
                {{ $restaurant->name }}
            </a>
        @endforeach
    </div>
@else
    <p>No hay restaurantes en esta categoría.</p>
@endif

<div class="mt-3">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Volver a categorías</a>
</div>
@endsection