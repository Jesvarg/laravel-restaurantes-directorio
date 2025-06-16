<form method="POST" action="{{ $restaurant->exists ? route('restaurants.update', $restaurant) : route('restaurants.store') }}" enctype="multipart/form-data" id="restaurantForm">
    @csrf
    @if ($restaurant->exists)
        @method('PUT')
    @endif

    <fieldset class="mb-4 p-3 border rounded">
        <legend class="w-auto px-2 h6 fw-bold"><i class="bi bi-info-circle-fill me-1"></i>Información Básica</legend>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label"><i class="bi bi-shop-window me-1 text-primary"></i>Nombre del Restaurante:</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $restaurant->name) }}" required placeholder="Ej: El Buen Sabor">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label"><i class="bi bi-telephone-fill me-1 text-primary"></i>Teléfono:</label>
                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       value="{{ old('phone', $restaurant->phone) }}" placeholder="Ej: 912345678">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label"><i class="bi bi-geo-alt-fill me-1 text-primary"></i>Dirección Completa:</label>
            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                   value="{{ old('address', $restaurant->address) }}" required placeholder="Ej: Calle Falsa 123, Ciudad">
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label"><i class="bi bi-blockquote-left me-1 text-primary"></i>Descripción Detallada:</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                      rows="4" placeholder="Describe tu restaurante, especialidades, ambiente, etc.">{{ old('description', $restaurant->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </fieldset>

    <fieldset class="mb-4 p-3 border rounded">
        <legend class="w-auto px-2 h6 fw-bold"><i class="bi bi-tags-fill me-1"></i>Categorías</legend>
        <div class="row">
            @forelse ($categories as $category)
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}"
                            @checked(in_array($category->id, old('categories', $restaurant->categories->pluck('id')->toArray())))
                            class="form-check-input">
                        <label for="category-{{ $category->id }}" class="form-check-label">{{ $category->name }}</label>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay categorías disponibles. <a href="{{ route('categories.create') }}">Crea una nueva</a>.</p>
            @endforelse
        </div>
        @error('categories')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
    </fieldset>

    <fieldset class="mb-4 p-3 border rounded">
        <legend class="w-auto px-2 h6 fw-bold"><i class="bi bi-images me-1"></i>Galería de Fotos</legend>
        <div class="mb-3">
            <label for="photos" class="form-label">Añadir nuevas fotos:</label>
            <input type="file" name="photos[]" id="photos" class="form-control @error('photos.*') is-invalid @enderror" multiple accept="image/jpeg,image/png,image/gif">
            <small class="form-text text-muted">Puedes seleccionar múltiples imágenes (JPG, PNG, GIF). Máximo 2MB por imagen.</small>
            @error('photos.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div id="image-preview-container" class="row g-2 mb-3"></div>

        @if($restaurant->exists && $restaurant->photos->count() > 0)
            <p class="fw-semibold mb-2">Fotos actuales (marca para eliminar):</p>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-2">
                @foreach($restaurant->photos as $photo)
                    <div class="col existing-photo-item">
                        <div class="card h-100">
                            <img src="{{ Storage::url($photo->url) }}" class="card-img-top" alt="Foto del restaurante" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2 text-center">
                                <div class="form-check">
                                    <input type="checkbox" name="photos_to_delete[]" value="{{ $photo->id }}" id="delete-photo-{{ $photo->id }}" class="form-check-input border-danger">
                                    <label for="delete-photo-{{ $photo->id }}" class="form-check-label text-danger"><small>Eliminar</small></label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </fieldset>

    <div class="mb-3">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <p class="text-muted"><small><i class="bi bi-person-fill me-1"></i>Propietario: {{ Auth::user()->name }}</small></p>
    </div>

    <hr>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ $restaurant->exists ? route('restaurants.show', $restaurant) : route('restaurants.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg me-1"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi {{ $iconClass ?? 'bi-check-circle-fill' }} me-1"></i> {{ $buttonText ?? ($restaurant->exists ? 'Actualizar Restaurante' : 'Guardar Restaurante') }}
        </button>
    </div>
</form>

@pushOnce('scripts')
<script src="{{ asset('js/image-preview.js') }}" defer></script>
@endPushOnce

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

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ isset($restaurant) ? 'Actualizar' : 'Guardar' }}
        </button>
        <a href="{{ route('restaurants.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>
</form>