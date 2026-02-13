
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Users
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($totalUsers) }}
                                </dd>
                                <dd class="text-xs text-gray-500 mt-1">
                                    {{ $totalFarmers }} farmers • {{ $totalBuyers }} buyers
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Verifications Card (IMPORTANT!) -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-user-clock text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Pending Verifications
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $pendingVerifications }}
                                </dd>
                                <dd class="text-xs text-gray-500 mt-1">
                                    <a href="{{ route('admin.farmers') }}" class="text-yellow-600 hover:text-yellow-700">
                                        <i class="fas fa-eye mr-1"></i> Review now
                                    </a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i class="fas fa-carrot text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Products
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($totalProducts) }}
                                </dd>
                                <dd class="text-xs text-gray-500 mt-1">
                                    {{ $activeProducts }} in stock
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Revenue
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    UGX {{ number_format($totalRevenue) }}
                                </dd>
                                <dd class="text-xs text-gray-500 mt-1">
                                    From {{ $totalOrders }} orders
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Activity -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach([
                ['icon' => 'shopping-cart', 'color' => 'blue', 'label' => 'Orders Today', 'value' => $platformStats['orders_today'] ?? 0],
                ['icon' => 'user-plus', 'color' => 'green', 'label' => 'New Users', 'value' => $platformStats['new_users_today'] ?? 0],
                ['icon' => 'carrot', 'color' => 'orange', 'label' => 'New Products', 'value' => $platformStats['products_added_today'] ?? 0],
                ['icon' => 'user-clock', 'color' => 'yellow', 'label' => 'Pending Farmers', 'value' => $platformStats['pending_farmers'] ?? 0],
            ] as $stat)
            <div class="bg-white rounded-lg shadow p-4 text-center hover:shadow-md transition">
                <div class="w-12 h-12 bg-{{ $stat['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                </div>
                <p class="text-sm text-gray-600 font-medium">{{ $stat['label'] }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- Recent Data Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Farmers -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Recent Farmers
                        </h3>
                        <a href="{{ route('admin.farmers') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="flow-root">
                        <ul class="divide-y divide-gray-200">
                            @forelse($recentFarmers as $farmer)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="{{ $farmer->profile_photo_url }}" 
                                             alt="{{ $farmer->name }}">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $farmer->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $farmer->email }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($farmer->verification_status == 'verified') bg-green-100 text-green-800
                                            @elseif($farmer->verification_status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($farmer->verification_status) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4 text-center text-gray-500">
                                No farmers registered yet
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Recent Orders
                        </h3>
                        
                            View all →
                        </a>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="flow-root">
                        <ul class="divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                            @php
                                $farmerNames = $order->items
                                    ->map(fn ($item) => $item->farmer->name ?? null)
                                    ->filter()
                                    ->unique()
                                    ->values()
                                    ->join(', ');
                                $orderStatus = $order->computed_status ?? $order->status;
                                $orderStatusLabel = $orderStatus === 'pending_payment' ? 'Pending Payment' : ucfirst($orderStatus);
                                $placedAt = $order->created_at?->format('M d, Y h:i A');
                                $completedAt = $orderStatus === 'completed' ? $order->updated_at?->format('M d, Y h:i A') : null;
                                $canceledAt = $orderStatus === 'cancelled' ? $order->updated_at?->format('M d, Y h:i A') : null;
                            @endphp
                            <li class="py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            Order #{{ $order->id }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Buyer: {{ $order->buyer->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Farmer(s): {{ $farmerNames ?: 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            UGX {{ number_format($order->total_amount) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Placed: {{ $placedAt ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Completed: {{ $completedAt ?? 'N/A' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Canceled: {{ $canceledAt ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($orderStatus === 'completed') bg-green-100 text-green-800
                                            @elseif($orderStatus === 'pending' || $orderStatus === 'pending_payment') bg-yellow-100 text-yellow-800
                                            @elseif($orderStatus === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $orderStatusLabel }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4 text-center text-gray-500">
                                No orders yet
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.farmers') }}" 
                   class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-6 text-center transition duration-150 ease-in-out transform hover:-translate-y-1">
                    <div class="mb-3">
                        <i class="fas fa-user-check text-blue-600 text-3xl"></i>
                    </div>
                    <h4 class="font-medium text-blue-900">Verify Farmers</h4>
                    <p class="text-sm text-blue-700 mt-2">Review pending farmer applications</p>
                </a>
                
                <a href="" 
                   class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-6 text-center transition duration-150 ease-in-out transform hover:-translate-y-1">
                    <div class="mb-3">
                        <i class="fas fa-carrot text-green-600 text-3xl"></i>
                    </div>
                    <h4 class="font-medium text-green-900">Manage Products</h4>
                    <p class="text-sm text-green-700 mt-2">View and manage all products</p>
                </a>
                
                <a href="" 
                   class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-6 text-center transition duration-150 ease-in-out transform hover:-translate-y-1">
                    <div class="mb-3">
                        <i class="fas fa-shopping-bag text-purple-600 text-3xl"></i>
                    </div>
                    <h4 class="font-medium text-purple-900">Manage Orders</h4>
                    <p class="text-sm text-purple-700 mt-2">View and update orders</p>
                </a>
                
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-6 text-center transition duration-150 ease-in-out transform hover:-translate-y-1">
                    <div class="mb-3">
                        <i class="fas fa-user text-gray-600 text-3xl"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">User Dashboard</h4>
                    <p class="text-sm text-gray-700 mt-2">Switch to user view</p>
                </a>
            </div>
        </div>

    </div>
</div>
