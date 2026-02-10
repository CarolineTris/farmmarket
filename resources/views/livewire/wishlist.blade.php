<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-green-800">❤️ My Wishlist</h1>
            <p class="text-gray-600">Save products for later</p>
        </div>

        @if($wishlistItems->count() > 0)
            <!-- Wishlist Actions -->
            <div class="mb-6 flex justify-between items-center">
                <p class="text-gray-700">{{ $wishlistItems->count() }} item(s) saved</p>
                <button wire:click="clearWishlist" 
                        onclick="return confirm('Clear all items from wishlist?')"
                        class="text-red-600 hover:text-red-700 text-sm">
                    <i class="fas fa-trash mr-1"></i> Clear All
                </button>
            </div>

            <!-- Wishlist Items Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlistItems as $product)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                        <!-- Product Image -->
                        <div class="h-48 overflow-hidden rounded-t-lg relative">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-carrot text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Remove Button -->
                            <button wire:click="removeFromWishlist({{ $product->id }})"
                                    class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center hover:bg-red-50 text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $product->farmer->name }}</p>
                                </div>
                                <span class="text-green-700 font-bold">UGX {{ number_format($product->price) }}</span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">
                                    @if($product->quantity > 0)
                                        <span class="text-green-600">In stock</span>
                                    @else
                                        <span class="text-red-600">Out of stock</span>
                                    @endif
                                </span>
                                
                                <div class="flex space-x-2">
                                    <button wire:click="moveToCart({{ $product->id }})"
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State if items become 0 -->
        @else
            <!-- Empty Wishlist -->
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-heart text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-6">Save products you love for quick access later</p>
                <a href="{{ route('marketplace') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-store mr-2"></i> Browse Marketplace
                </a>
            </div>
        @endif

    </div>
</div>
