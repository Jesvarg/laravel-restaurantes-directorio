<form method="POST" action="{{ isset($restaurant) ? route('restaurants.update', $restaurant) : route('restaurants.store') }}" enctype="multipart/form-data">
    @csrf
    @isset($restaurant)
        @method('PUT')
    @endisset

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">
                    <i class="bi bi-shop text-primary"></i> Nombre
                </label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $restaurant->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="phone" class="form-label">
                    <i class="bi bi-telephone text-primary"></i> Teléfono
                </label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $restaurant->phone ?? '') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">
            <i class="bi bi-geo-alt text-primary"></i> Dirección
        </label>
        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
               value="{{ old('address', $restaurant->address ?? '') }}" required>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">
            <i class="bi bi-card-text text-primary"></i> Descripción
        </label>
        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                  rows="4">{{ old('description', $restaurant->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="user_id" class="form-label">
            <i class="bi bi-person text-primary"></i> Propietario
        </label>
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
    </div>

    <div class="mb-4">
        <label class="form-label">
            <i class="bi bi-tags text-primary"></i> Categorías
        </label>
        <div class="row">
            @foreach ($categories as $category)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}"
                            @if (isset($restaurant) && $restaurant->categories->contains($category)) checked @endif
                            class="form-check-input">
                        <label for="category-{{ $category->id }}" class="form-check-label">{{ $category->name }}</label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('categories')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">
            <i class="bi bi-images text-primary"></i> Fotos
        </label>
        <div class="input-group mb-3">
            <input type="file" name="photos[]" class="form-control @error('photos.*') is-invalid @enderror" multiple accept="image/*">
            <label class="input-group-text" for="photos">Subir</label>
            @error('photos.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <small class="text-muted">Puedes seleccionar múltiples imágenes. Formatos permitidos: JPG, PNG, GIF.</small>
        
        @if(isset($restaurant) && $restaurant->photos->count() > 0)
            <div class="mt-3">
                <p class="fw-bold">Fotos actuales:</p>
                <div class="row">
                    @foreach($restaurant->photos as $photo)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img src="{{ $photo->url }}" class="card-img-top" alt="Foto" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <div class="form-check">
                                        <input type="checkbox" name="photos_to_delete[]" value="{{ $photo->id }}" id="delete-photo-{{ $photo->id }}" class="form-check-input">
                                        <label for="delete-photo-{{ $photo->id }}" class="form-check-label text-danger">Eliminar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>