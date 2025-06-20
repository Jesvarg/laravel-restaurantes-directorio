<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Directorio de Restaurantes')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts y Estilos con Vite -->
    @production
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/js/app.js']['css'][0]) }}">
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction

    <!-- Estilos adicionales de las páginas -->
    @yield('styles')
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div id="app" class="flex flex-col min-h-screen">
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center text-xl font-bold text-red-500 hover:text-red-600">
                            <i class="bi bi-geo-alt-fill mr-2"></i> DirectorioRest
                        </a>
                        
                        <!-- Navigation Links -->
                        <div class="hidden md:flex ml-10 space-x-8">
                            <a href="{{ route('restaurants.index') }}" class="text-gray-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                                {{ __('Restaurantes') }}
                            </a>
                            <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                                {{ __('Categorías') }}
                            </a>
                            <a href="{{ route('reviews.index') }}" class="text-gray-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                                {{ __('Reseñas') }}
                            </a>
                        </div>
                    </div>

                    <!-- Right Side Of Navbar -->
                    <div class="flex items-center space-x-4">
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                                    {{ __('Login') }}
                                </a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @else
                            <div class="relative group">
                                <button class="flex items-center text-gray-700 hover:text-red-500 px-3 py-2 text-sm font-medium transition-colors">
                                    {{ Auth::user()->name }}
                                    <i class="bi bi-chevron-down ml-1"></i>
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <a href="{{ route('restaurants.favorites') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-heart-fill mr-2"></i>Mis Favoritos
                                    </a>
                                    <a href="{{ route('reviews.my') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-chat-dots mr-2"></i>Mis Reseñas
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="text-gray-700 hover:text-red-500 focus:outline-none focus:text-red-500" onclick="toggleMobileMenu()">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 pt-4 pb-3">
                    <div class="space-y-1">
                        <a href="{{ route('restaurants.index') }}" class="block px-3 py-2 text-gray-700 hover:text-red-500 text-sm font-medium">
                            {{ __('Restaurantes') }}
                        </a>
                        <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-gray-700 hover:text-red-500 text-sm font-medium">
                            {{ __('Categorías') }}
                        </a>
                        <a href="{{ route('reviews.index') }}" class="block px-3 py-2 text-gray-700 hover:text-red-500 text-sm font-medium">
                            {{ __('Reseñas') }}
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="bg-gray-800 text-white py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-gray-300">Directorio de Restaurantes © {{ date('Y') }}. Todos los derechos reservados.</span>
            </div>
        </footer>
    </div>
    
    <!-- Scripts adicionales de las páginas -->
    @yield('scripts')
    @stack('scripts')
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
