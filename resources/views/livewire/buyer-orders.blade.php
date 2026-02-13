<div class="max-w-7xl mx-auto px-4 py-6">
  <!-- Add at the top of the file -->
  <div class="mb-4">
      <a href="{{ route('buyer.dashboard') }}" 
        class="inline-flex items-center text-green-600 hover:text-green-700">
          <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
      </a>
  </div>
  <h2 class="text-2xl font-bold text-green-800 mb-6">🛒 My Orders</h2>

  @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if($orders->count())
    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($orders as $order)
            @foreach($order->items as $item)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->product->name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->product->farmer->name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity ?? 1 }}</td>
                <td class="px-4 py-3 text-sm text-gray-900">UGX {{ number_format($item->subtotal ?? $order->total_amount) }}</td>
                <td class="px-4 py-3">
                    <!-- Show item status if available, otherwise order status -->
                    @php
                        $orderStatus = $order->computed_status ?? $order->status;
                        $itemStatus = $orderStatus === 'pending_payment'
                            ? 'pending_payment'
                            : ($item->status ?? $orderStatus);
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($itemStatus === 'completed') bg-green-100 text-green-800
                        @elseif($itemStatus === 'pending' || $itemStatus === 'pending_payment') bg-yellow-100 text-yellow-800
                        @elseif($itemStatus === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $itemStatus === 'pending_payment' ? 'Pending Payment' : ucfirst($itemStatus) }}
                    </span>
                </td>                         
              <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-500 space-x-2">
                @if($item->status === 'pending')
                  <button 
                    wire:click="cancelItem({{ $item->id }})" 
                    class="text-red-600 hover:underline"
                    onclick="return confirm('Are you sure you want to cancel this order?')">
                    Cancel Item
                  </button>
                @else
                  <span class="text-gray-400">No actions</span>
                @endif
              </td>
            </tr>
            @endforeach
            @php
              $orderStatus = $order->computed_status ?? $order->status;
              $orderStatusLabel = $orderStatus === 'pending_payment' ? 'Pending Payment' : ucfirst($orderStatus);
            @endphp
            <tr class="bg-gray-50">
              <td class="px-4 py-2 text-sm text-gray-600" colspan="7">
                <div class="flex items-center justify-between">
                  <span>Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}</span>
                  @if($orderStatus === 'pending' || $orderStatus === 'pending_payment')
                    <button 
                      wire:click="cancelOrder({{ $order->id }})" 
                      class="text-red-600 hover:underline"
                      onclick="return confirm('Are you sure you want to cancel the entire order?')">
                      Cancel Order
                    </button>
                  @else
                    <span class="text-gray-400">Order {{ $orderStatusLabel }}</span>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $orders->links() }}
    </div>
  @else
    <div class="bg-white p-6 rounded shadow text-center text-gray-500">
      You haven’t placed any orders yet. <a href="{{ route('marketplace') }}" class="text-green-600 hover:underline">Start shopping!</a>
    </div>
  @endif
</div>
