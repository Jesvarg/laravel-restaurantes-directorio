@extends('layouts.app')
@section('title', 'Categorías')

@section('content')
<h2>Categorías de Restaurantes</h2>

@can('create', App\Models\Category::class)
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Nueva Categoría</a>
@endcan

<div class="row">
    @foreach ($categories as $category)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="card-text">{{ $category->restaurants_count }} restaurantes</p>
                    <div class="d-flex">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info me-2">Ver</a>
                        <a href="{{ route('categories.restaurants', $category) }}" class="btn btn-sm btn-success me-2">Ver restaurantes</a>
                        
                        @can('update', $category)
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning me-2">Editar</a>
                        @endcan
                        
                        @can('delete', $category)
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection