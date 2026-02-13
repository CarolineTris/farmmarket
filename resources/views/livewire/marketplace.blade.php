<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-green-800">🌾 FarmMarket</h1>
            <p class="text-gray-600 mt-2">Fresh produce directly from local farmers</p>
        </div>

        <!-- Add at the top of the file, after the header -->
        <div class="mb-4">
            <a href="{{ route('buyer.dashboard') }}" 
            class="inline-flex items-center text-green-600 hover:text-green-700">
                <i class="fas fa-home mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Search and Filters Bar -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search products or farmers..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-full md:w-48">
                    <select wire:model.live="category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="w-full md:w-48">
                    <select wire:model.live="sortBy" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Sort By</option>
                        @foreach($sortOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters -->
                @if($search || $category || $minPrice || $maxPrice)
                    <button wire:click="clearFilters" 
                            class="px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Clear Filters
                    </button>
                @endif
            </div>

            <!-- Price Range -->
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Min Price (UGX)</label>
                    <input type="number" 
                           wire:model.live.debounce.300ms="minPrice"
                           placeholder="Min"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Max Price (UGX)</label>
                    <input type="number" 
                           wire:model.live.debounce.300ms="maxPrice"
                           placeholder="Max"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">
                @if($products->total() > 0)
                    Showing <span class="font-semibold">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> 
                    of <span class="font-semibold">{{ $products->total() }}</span> products
                @else
                    No products found
                @endif
            </p>
            
            <!--cart -->
            <a href="{{ route('cart') }}"
                class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <span>View Cart</span>
            </a>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Product Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-carrot text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-2 right-2">
                                @if($product->quantity < 10)
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        Low Stock
                                    </span>
                                @else
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                                        In Stock
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <!-- Farmer Info -->
                            <div class="flex items-center mb-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm text-gray-600">{{ $product->farmer->name }}</span>
                            </div>

                            <!-- Product Name -->
                            <h3 class="font-semibold text-gray-800 mb-1 truncate">{{ $product->name }}</h3>
                            
                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                            <!-- Category & Quantity -->
                            <div class="flex justify-between text-xs text-gray-500 mb-3">
                                <span class="bg-gray-100 px-2 py-1 rounded">{{ $categories[$product->category] ?? 'Uncategorized' }}</span>
                                <span>Qty: {{ $product->quantity }}</span>
                            </div>

                            <!-- Price and Action -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-bold text-green-700">UGX {{ number_format($product->price) }}</p>
                                    @if($product->unit)
                                        <p class="text-xs text-gray-500">per {{ $product->unit }}</p>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <button wire:click="viewProduct({{ $product->id }})"
                                            class="text-blue-600 hover:text-blue-700 transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button wire:click="addToCart({{ $product->id }})"
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <i class="fas fa-cart-plus mr-1"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-xl shadow">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 mb-6">
                    @if($search)
                        No products match "{{ $search }}". Try a different search term.
                    @else
                        No products available in this category.
                    @endif
                </p>
                @if($search || $category)
                    <button wire:click="clearFilters" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Clear Filters & Show All
                    </button>
                @endif
            </div>
        @endif

        <!-- Featured Farmers Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-green-800 mb-6">⭐ Featured Farmers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($this->getFeaturedFarmers() as $farmer)
                    <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-tie text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $farmer->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $farmer->products_count }} products</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">{{ $farmer->bio ?? 'Experienced local farmer' }}</p>
                        <a href="{{ route('farmers.show', $farmer->id) }}" 
                           class="text-green-600 hover:text-green-700 font-medium">
                            View Products →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Why Buy From Us -->
        <div class="mt-12 bg-green-50 rounded-xl p-8">
            <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Why Buy From FarmMarket?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-leaf text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Fresh From Farm</h4>
                    <p class="text-sm text-gray-600">Direct from local farmers, harvested daily</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Support Local</h4>
                    <p class="text-sm text-gray-600">Support small-scale farmers in your community</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tag text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Best Prices</h4>
                    <p class="text-sm text-gray-600">No middlemen, better prices for you</p>
                </div>
            </div>
        </div>


<div
    x-data="{ show: false, message: '', type: 'success' }"
    x-on:show-toast.window="
        type = $event.detail.type || 'success';
        message = $event.detail.message || '';
        show = true;
        setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition
    class="fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white"
    :class="type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600')"
    style="display: none;"
>
    <div class="flex items-center">
        <i class="fas mr-2" :class="type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-times-circle' : 'fa-info-circle')"></i>
        <span x-text="message"></span>
    </div>
</div>

    </div>
</div>

@push('scripts')
<script>
    // Listen for cart updates
    Livewire.on('cart-updated', () => {
        // You can add cart update logic here
        console.log('Cart updated');
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any JavaScript plugins here
    });
</script>
@endpush
