<div>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- Page Header -->
               <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">My Product Listings</h1>
        <p class="text-gray-600 mt-2">Manage your farm products and inventory</p>
    </div>
    <div class="flex space-x-3 mt-4 md:mt-0">
        <!-- Back to Farmer Dashboard -->
        <a href="{{ route('farmer.dashboard') }}"
           class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>

        <!-- Add New Product -->
        <a href="{{ route('products.create') }}"
           class="bg-teal-600 text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Add New Product
        </a>
    </div>
</div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">All Products</h2>
            </div>
            
            @if($products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-carrot text-green-600"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">UGX {{ number_format($product->price) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->quantity > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                            In Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>
                                            Out of Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900 transition flex items-center">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No products listed</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first product to the marketplace.</p>
                    <a href="{{ route('products.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i> Add Your First Product
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        @if($products->count() > 0)
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-file-export text-blue-600 mr-3"></i>
                        <span>Export Product List</span>
                    </button>
                    
                    <!-- View Sales Button -->
                    <button wire:click="toggleAnalytics"
                            class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                        <span>View Sales Analytics</span>
                    </button>

                    <!-- Modal -->
                    @if($showAnalytics)
                    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Analytics</h3>
                            
                            <!-- Chart -->
                            <canvas id="salesChart"></canvas>

                            <!-- Close Button -->
                            <div class="mt-4 flex justify-end">
                                <button wire:click="$set('showAnalytics', false)" 
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                                    </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Alerts</h3>
                <div class="space-y-3">
                    @foreach($products->where('quantity', '<', 10)->where('quantity', '>', 0) as $lowStockProduct)
                    <div class="flex items-center justify-between px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <span class="text-sm font-medium">{{ $lowStockProduct->name }}</span>
                        </div>
                        <span class="text-sm text-yellow-700">Low stock: {{ $lowStockProduct->quantity }}</span>
                    </div>
                    @endforeach
                    
                    @if($products->where('quantity', '<', 10)->where('quantity', '>', 0)->count() === 0)
                    <p class="text-sm text-gray-500 text-center py-4">All products have sufficient stock</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', (message, component) => {
        // Only run when modal is open
        if (@json($showAnalytics)) {
            const ctx = document.getElementById('salesChart');
            if (ctx && !ctx.dataset.initialized) {
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($products->pluck('name')),
                        datasets: [
                            {
                                label: 'Quantity',
                                data: @json($products->pluck('quantity')),
                                backgroundColor: 'rgba(34,197,94,0.7)',
                            },
                            {
                                label: 'Revenue (UGX)',
                                data: @json($products->map(fn($p) => $p->price * $p->quantity)),
                                backgroundColor: 'rgba(59,130,246,0.7)', // Tailwind blue-500
                            }
                        ]
                    }
                });
                ctx.dataset.initialized = true; // prevent duplicate charts
            }
        }
    });
});
</script>
@endpush