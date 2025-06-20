<div class="restaurant-card">
    @if($restaurant->photos->isNotEmpty())
        <img src="{{ asset('storage/' . $restaurant->photos->first()->path) }}" 
             alt="{{ $restaurant->name }}" 
             class="restaurant-image">
    @else
        <div class="restaurant-image bg-gray-100 flex items-center justify-center">
            <i class="bi bi-image text-gray-400 text-5xl"></i>
        </div>
    @endif
    
    <div class="card-body flex flex-col h-full">
        <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $restaurant->name }}</h5>
        <p class="text-gray-600 text-sm mb-3 flex items-center">
            <i class="bi bi-geo-alt-fill text-red-500 mr-1"></i>{{ Str::limit($restaurant->address, 60) }}
        </p>
        
        <div class="flex items-center mb-2 flex-wrap gap-1">
            @forelse($restaurant->categories->take(2) as $category)
                <a href="{{ route('categories.show', $category) }}" class="badge badge-info hover:bg-blue-200 transition-colors no-underline">{{ $category->name }}</a>
            @empty
                <span class="badge badge-secondary">Sin categoría</span>
            @endforelse
            @if($restaurant->categories->count() > 2)
                <span class="badge badge-secondary">+{{ $restaurant->categories->count() - 2 }}</span>
            @endif
        </div>
        
        <div class="flex items-center mb-3">
            <div class="rating-stars mr-2">
                @php
                    $rating = $restaurant->averageRating();
                    $fullStars = floor($rating);
                    $halfStar = $rating - $fullStars >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp
                @for($i = 0; $i < $fullStars; $i++) <i class="bi bi-star-fill"></i> @endfor
                @if($halfStar) <i class="bi bi-star-half"></i> @endif
                @for($i = 0; $i < $emptyStars; $i++) <i class="bi bi-star"></i> @endfor
            </div>
            <small class="text-gray-500">
                ({{ number_format($rating, 1) }}) • {{ $restaurant->reviews()->count() }} reseñas
            </small>
        </div>
        
        <div class="mt-auto flex justify-between items-center pt-2">
            <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-outline-primary text-sm">
                <i class="bi bi-eye mr-1"></i>Ver detalles
            </a>
            
            @auth
                @if(in_array($restaurant->id, $favoriteRestaurantIds ?? []))
                    <form action="{{ route('restaurants.unfavorite', $restaurant) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 p-2" title="Quitar de favoritos">
                            <i class="bi bi-heart-fill text-lg"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('restaurants.favorite', $restaurant) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-2" title="Agregar a favoritos">
                            <i class="bi bi-heart text-lg"></i>
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</div>
