<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FarmMarket Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <!-- Admin Navigation -->
    <nav class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <i class="fas fa-seedling text-green-400 text-xl"></i>
                        <span class="text-xl font-bold">FarmMarket Admin</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.farmers') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.farmers') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-users mr-1"></i> Farmers
                    </a>
                    <a href="" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.products') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-carrot mr-1"></i> Products
                    </a>
                    <a href="" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.orders') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <i class="fas fa-shopping-bag mr-1"></i> Orders
                    </a>
                </div>
                
                <!-- Right Side: User Menu -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" 
                       class="text-sm hover:text-gray-300 transition flex items-center">
                        <i class="fas fa-external-link-alt mr-1"></i> User Dashboard
                    </a>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 text-sm hover:text-gray-300 focus:outline-none">
                            <img class="h-8 w-8 rounded-full" 
                                 src="{{ Auth::user()->profile_photo_url }}" 
                                 alt="{{ Auth::user()->name }}">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10"
                             style="display: none;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="md:hidden" x-data="{ open: false }">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : '' }} block px-3 py-2 rounded-md text-base font-medium">
                    Dashboard
                </a>
                <a href="{{ route('admin.farmers') }}" 
                   class="{{ request()->routeIs('admin.farmers') ? 'bg-gray-900' : '' }} block px-3 py-2 rounded-md text-base font-medium">
                    Farmers
                </a>
                <a href="" 
                   class="{{ request()->routeIs('admin.products') ? 'bg-gray-900' : '' }} block px-3 py-2 rounded-md text-base font-medium">
                    Products
                </a>
                <a href="" 
                   class="{{ request()->routeIs('admin.orders') ? 'bg-gray-900' : '' }} block px-3 py-2 rounded-md text-base font-medium">
                    Orders
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @yield('header')
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </main>

    @livewireScripts
    
    <!-- Alpine.js for dropdowns -->
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('[class*="bg-"]');
                alerts.forEach(alert => {
                    if (alert.classList.contains('bg-green-100') || alert.classList.contains('bg-red-100')) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html>