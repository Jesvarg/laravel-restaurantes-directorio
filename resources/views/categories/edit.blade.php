@extends('layouts.app')
@section('title', 'Editar Categoría')

@section('content')
<h2>Editar Categoría</h2>

<form method="POST" action="{{ route('categories.update', $category) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $category->name) }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection