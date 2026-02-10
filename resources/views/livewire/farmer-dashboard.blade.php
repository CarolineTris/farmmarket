<div class="bg-[#e6f4e6] min-h-screen flex flex-col justify-between">
  <div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-center text-green-800 mb-6">
      Welcome, {{ Auth::user()->name }}
    </h2>
    @php
        $user = auth()->user();
    @endphp

    @if ($user->isPendingVerification())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-6 text-center">
            <strong>⏳ Account Under Review</strong>
            <p class="mt-1 text-sm">
                Your farmer account is pending verification.  
                You’ll get full access once an administrator approves your account.
            </p>
        </div>
    @endif


    <!-- Marketplace Insight -->
    <div @if($user->isPendingVerification()) class="opacity-50 pointer-events-none" @endif>

    <div class="bg-gradient-to-r from-green-200 via-teal-100 to-green-100 p-6 rounded-xl shadow mb-10 text-center">
      <h3 class="text-lg font-semibold text-green-900 mb-2">🌾 Marketplace Insight</h3>
      <p class="text-sm text-gray-700">
        Buyers are most active between <strong>8AM–11AM</strong>. Listings with photos get 3× more views!
      </p>
    </div>

    <!-- Feature Cards -->
<!-- Feature Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
 <!-- My Listings -->
<a href="{{ route('farmer.listings') }}" 
   class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/listings.png') }}" class="w-10 h-10" alt="Listings">
    <div>
      <p class="text-sm text-gray-600">My Listings</p>
      <p class="text-green-700 font-bold text-lg">Create & Manage</p>
    </div>
</a>

<!-- Orders -->
<a href="{{ route('farmer.orders') }}" 
   class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/purchases.png') }}" class="w-10 h-10" alt="Orders">
    <div>
      <p class="text-sm text-gray-600">Orders</p>
      <p class="text-blue-600 font-bold text-lg">Monitor Orders</p>
    </div>
</a>
  <div class="bg-white rounded-lg shadow p-6 flex items-center gap-4 hover:shadow-lg transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/messages.png') }}" class="w-10 h-10" alt="Analytics">
    <div>
      <p class="text-sm text-gray-600">Messages</p>
      <p class="text-gray-700 font-bold text-lg">Connect with Buyers</p>
    </div>
  </div>
</div>

  <!-- Summary Stats -->
<div wire:poll.10s class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <div class="bg-green-100 text-green-900 p-6 rounded-xl shadow flex flex-col items-start hover:shadow-md transition">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">📦</span>
            <p class="text-sm font-semibold">Total Listings</p>
        </div>
        <p class="text-4xl font-bold">{{ $totalListings }}</p>
        <span class="text-xs bg-green-700 text-white px-2 py-1 rounded-full mt-2">{{ $totalListings }} Active</span>
    </div>

    <div class="bg-teal-100 text-teal-900 p-6 rounded-xl shadow flex flex-col items-start hover:shadow-md transition">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">🛒</span>
            <p class="text-sm font-semibold">Active Orders</p>
        </div>
        <p class="text-4xl font-bold">{{ $totalPurchases }}</p>
        <span class="text-xs bg-teal-700 text-white px-2 py-1 rounded-full mt-2">{{ $totalPurchases }} Pending</span>
    </div>

    <div class="bg-gray-100 text-gray-800 p-6 rounded-xl shadow flex flex-col items-start hover:shadow-md transition">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">✅</span>
            <p class="text-sm font-semibold">Completed Sales</p>
        </div>
        <p class="text-4xl font-bold">{{ $completedSales }}</p>
        <span class="text-xs bg-gray-700 text-white px-2 py-1 rounded-full mt-2">{{ $completedSales }} Sales</span>
    </div>

    <div class="bg-lime-100 text-green-900 p-6 rounded-xl shadow flex flex-col items-start hover:shadow-md transition">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xl">💰</span>
            <p class="text-sm font-semibold">Total Revenue</p>
        </div>
        <p class="text-4xl font-bold">{{ number_format($earnings) }}</p>
        <span class="text-xs bg-green-800 text-white px-2 py-1 rounded-full mt-2">UGX</span>
    </div>
</div>

    <!-- Sales Chart -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
      <h3 class="text-lg font-semibold text-green-800 mb-4">📈 Sales Analytics</h3>
      <livewire:sales-chart />
    </div>
    

    <!-- Farmer Tip -->
    <div class="max-w-3xl mx-auto bg-green-100 p-6 rounded shadow text-justify">
      <h3 class="text-lg font-semibold text-green-800 mb-2">🌱 Farmer Tip</h3>
      <p class="text-sm text-gray-700">
        Add clear photos and detailed descriptions to attract more buyers and increase sales.
        Highlight freshness, quantity, and delivery options to build trust and boost conversions.
      </p>
    </div>
  </div>
</div>
  <!-- Footer -->
  @include('partials.footer')
</div>
