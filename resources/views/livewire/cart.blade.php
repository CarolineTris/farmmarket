<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-6">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-green-800">🛒 Shopping Cart</h1>
            <p class="text-gray-600">Review your items before checkout</p>
        </div>

        <!-- Add at the top of the file -->
        <div class="mb-4">
            <a href="{{ route('buyer.dashboard') }}" 
            class="inline-flex items-center text-green-600 hover:text-green-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- Cart Items -->
        @if(count($cartItems) > 0)
            <!-- Items List -->
            <div class="bg-white rounded-lg shadow mb-6">
                <!-- Cart Actions -->
                <div class="p-4 border-b flex justify-between items-center">
                    <span class="text-gray-700">{{ count($cartItems) }} item(s) in cart</span>
                    <button wire:click="clearCart" 
                            onclick="return confirm('Clear all items from cart?')"
                            class="text-red-600 hover:text-red-700 text-sm">
                        <i class="fas fa-trash mr-1"></i> Clear Cart
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="divide-y">
                    @foreach($cartItems as $productId => $item)
                        <div class="p-4 flex items-center">
                            <!-- Product Image/Icon -->
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4 overflow-hidden flex-shrink-0">
                                @if(isset($item['image']) && $item['image'])
                                    <img src="{{ route('media', ['path' => $item['image']]) }}" 
                                         alt="{{ $item['name'] }}"
                                         class="w-16 h-16 object-cover">
                                @else
                                    <i class="fas fa-carrot text-gray-400 text-xl"></i>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-800">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-600">Farmer: {{ $item['farmer_name'] ?? 'Unknown' }}</p>
                                <p class="text-green-700 font-bold">UGX {{ number_format($item['price']) }}</p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2 mr-6">
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})"
                                        class="w-8 h-8 border rounded-full flex items-center justify-center hover:bg-gray-50">
                                    <i class="fas fa-minus text-sm"></i>
                                </button>
                                
                                <span class="w-10 text-center font-medium">{{ $item['quantity'] }}</span>
                                
                                <button wire:click="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})"
                                        class="w-8 h-8 border rounded-full flex items-center justify-center hover:bg-gray-50">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>
                            </div>

                            <!-- Item Total & Remove -->
                            <div class="text-right">
                                <p class="font-bold text-gray-800">
                                    UGX {{ number_format($item['price'] * $item['quantity']) }}
                                </p>
                                <button wire:click="removeItem({{ $productId }})"
                                        class="text-red-600 hover:text-red-700 text-sm mt-1">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="p-4 border-t bg-gray-50">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-700">Subtotal</span>
                        <span class="font-bold text-gray-800">UGX {{ number_format($total) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-700">Delivery Fee</span>
                        <span class="text-gray-800">To be calculated</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-t pt-4">
                        <span class="text-lg font-bold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-green-700">UGX {{ number_format($total) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('marketplace') }}"
                   class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center">
                    <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                </a>
                
                <button wire:click="checkout"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold">
                    Proceed to Checkout
                </button>
            </div>

            <!-- Help Info -->
            <div class="mt-8 bg-blue-50 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-700">
                            <strong>Note:</strong> Each farmer ships separately. You may receive multiple deliveries.
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            Need help? Contact the farmer directly for order-specific questions.
                        </p>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Add some fresh produce from our marketplace!</p>
                <a href="{{ route('marketplace') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-store mr-2"></i> Browse Marketplace
                </a>
            </div>
        @endif


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
