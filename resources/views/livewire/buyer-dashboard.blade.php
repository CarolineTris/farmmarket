<div class="bg-[#e6f4e6] min-h-screen flex flex-col justify-between">
  <div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Header + back to marketplace -->
    <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-green-800">Welcome, {{ Auth::user()->name }}</h2>
    
    <div class="flex space-x-3">
        <!-- Wishlist Button -->
        <a href="{{ route('wishlist') }}" 
           class="flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-200">
            <i class="fas fa-heart mr-2"></i>
            <span class="font-medium">Wishlist</span>
            @auth
                @php
                    $wishlistCount = auth()->user()->wishlistItems()->count();
                @endphp
                @if($wishlistCount > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $wishlistCount }}
                    </span>
                @endif
            @endauth
        </a>
        
        <!-- Cart Button -->
        <a href="{{ route('cart') }}"
           class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm">
            <i class="fas fa-shopping-cart mr-2"></i>
            <span class="font-medium">Cart</span>
            @php
            use App\Models\CartItem;

            if (auth()->check()) {
                // Logged-in users → DB cart
                $cartCount = CartItem::where('user_id', auth()->id())
                    ->sum('quantity');
            } else {
                // Guests → session cart
                $cartCount = 0;
                $cart = session()->get('cart', []);
                foreach ($cart as $item) {
                    $cartCount += $item['quantity'] ?? 0;
                }
            }
        @endphp

            @if($cartCount > 0)
                <span class="ml-2 bg-white text-green-600 text-xs font-bold px-2 py-1 rounded-full">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
    </div>
</div>

    <!-- Insight -->
    <div class="bg-gradient-to-r from-blue-200 via-teal-100 to-green-100 p-6 rounded-xl shadow mb-10 text-center">
      <h3 class="text-lg font-semibold text-green-900 mb-2">🛒 Shopping Insight</h3>
      <p class="text-sm text-gray-700">Fresh produce arrives daily between <strong>6AM–9AM</strong>. Early orders get the best quality!</p>
    </div>

    <!-- Feature cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <a href="{{ route('marketplace') }}" class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition hover:scale-[1.02]">
        <img src="{{ asset('images/marketplace.png') }}" class="w-10 h-10" alt="Marketplace">
        <div>
          <p class="text-sm text-gray-600">Browse Marketplace</p>
          <p class="text-green-700 font-bold text-lg">Fresh Products</p>
        </div>
      </a>
      <a href="{{ route('buyer.orders') }}" class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition hover:scale-[1.02]">
        <img src="{{ asset('images/orders.png') }}" class="w-10 h-10" alt="Orders">
        <div>
          <p class="text-sm text-gray-600">My Orders</p>
          <p class="text-blue-600 font-bold text-lg">Track Purchases</p>
        </div>
      </a>
      
        <!-- Messages - to be worked on later -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition hover:scale-[1.02]">
          <img src="{{ asset('images/messages.png') }}" class="w-10 h-10" alt="Messages">
          <div>
            <p class="text-sm text-gray-600">Messages</p>
            <p class="text-gray-700 font-bold text-lg">Contact Farmers</p>
          </div>
        </div>
    </div>

    <!-- Summary stats -->
    <div class="flex justify-center mb-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-4xl">
        <div class="bg-blue-100 text-blue-900 p-6 rounded-xl shadow">
          <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">📦</span>
            <p class="text-sm font-semibold">Total Orders</p>
          </div>
          <p class="text-4xl font-bold">{{ $totalOrders }}</p>
          <span class="text-xs bg-blue-700 text-white px-2 py-1 rounded-full mt-2 inline-block">{{ $activeOrders }} Active</span>
        </div>

        <div class="bg-teal-100 text-teal-900 p-6 rounded-xl shadow">
          <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">💰</span>
            <p class="text-sm font-semibold">Monthly Spending</p>
          </div>
          <p class="text-4xl font-bold">{{ number_format($monthlySpending) }}</p>
          <span class="text-xs bg-teal-700 text-white px-2 py-1 rounded-full mt-2 inline-block">UGX</span>
        </div>

        <div class="bg-lime-100 text-green-900 p-6 rounded-xl shadow">
          <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">🌱</span>
            <p class="text-sm font-semibold">Savings vs Market</p>
          </div>
          <p class="text-4xl font-bold">{{ number_format($totalSavings) }}</p>
          <span class="text-xs bg-green-800 text-white px-2 py-1 rounded-full mt-2 inline-block">UGX Saved</span>
        </div>
      </div>
    </div>
    <!-- Recent orders -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
      <h3 class="text-lg font-semibold text-green-800 mb-4">📋 Recent Orders</h3>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
    @forelse($recentOrders as $order)
        @php
            $firstItem = $order->items->first();
            $orderStatus = $order->computed_status ?? $order->status;
            $orderStatusLabel = $orderStatus === 'pending_payment' ? 'Pending Payment' : ucfirst($orderStatus);
            $completedSubtotal = $order->items->where('status', 'completed')->sum('subtotal');
        @endphp
        @if($firstItem)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $firstItem->product->name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $firstItem->product->farmer->name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">UGX {{ number_format($completedSubtotal) }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($orderStatus === 'completed' || $orderStatus === 'delivered') bg-green-100 text-green-800
                        @elseif($orderStatus === 'pending' || $orderStatus === 'pending_payment') bg-yellow-100 text-yellow-800
                        @elseif($orderStatus === 'shipped') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $orderStatusLabel }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                No orders yet. <a href="{{ route('marketplace') }}" class="text-green-600 hover:underline">Start shopping!</a>
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
      </div>
      @if(count($recentOrders) > 0)
      <div class="mt-4 text-center">
        <a href="{{ route('buyer.orders') }}" class="text-green-600 hover:text-green-700 font-medium">View All Orders →</a>
      </div>
      @endif
    </div>

     <!-- Recommended Farmers -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
      <h3 class="text-lg font-semibold text-green-800 mb-4">⭐ Recommended Farmers</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($recommendedFarmers as $farmer)
        <div class="border rounded-lg p-4 hover:shadow-md transition">
          <p class="font-medium text-gray-900">{{ $farmer->name }}</p>
          <p class="text-sm text-gray-600">Location: {{ $farmer->profile->location ?? 'N/A' }}</p>
          <a href="{{ route('farmers.show', $farmer->id) }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
            View Products →
          </a>
        </div>
        @empty
        <p class="text-gray-500">No recommendations yet.</p>
        @endforelse
      </div>
    </div>


    <!-- Seasonal products -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
      <h3 class="text-lg font-semibold text-green-800 mb-4">🌽 Seasonal Specials</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @forelse($seasonalProducts as $product)
          <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="w-full h-32 bg-gray-100 rounded-lg mb-3 overflow-hidden">
              @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
              @endif
            </div>
            <h4 class="font-medium text-gray-900 mb-1">{{ $product->name }}</h4>
            <p class="text-sm text-gray-600 mb-2">{{ $product->farmer->name }}</p>
            <div class="flex justify-between items-center">
              <span class="text-green-700 font-bold">UGX {{ number_format($product->price) }}</span>
              <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded">In Season</span>
            </div>
          </div>
        @empty
          <p class="text-gray-500">No seasonal products right now.</p>
        @endforelse
      </div>
    </div>

    <!-- Shopping tip -->
    <div class="max-w-3xl mx-auto bg-blue-100 p-6 rounded shadow text-justify">
      <h3 class="text-lg font-semibold text-blue-800 mb-2">💡 Shopping Tip</h3>
      <p class="text-sm text-gray-700">
        Order early in the morning for the freshest produce. Build relationships with trusted farmers for better prices and consistent quality. Leave reviews to help other buyers!
      </p>
    </div>

  </div>

  @include('partials.footer')
</div>
