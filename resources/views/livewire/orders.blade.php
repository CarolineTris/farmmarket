<div class="p-6">
    
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-green-800">My Orders</h2>

        <!-- Back to Farmer Dashboard -->
        <a href="{{ route('farmer.dashboard') }}"
        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>

    <table class="w-full mt-6 border">
        <thead class="bg-teal-100">
            <tr>
                <th class="p-2">Product</th>
                <th class="p-2">Buyer</th>
                <th class="p-2">Quantity</th>
                <th class="p-2">Total</th>
                <th class="p-2">Status</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($items && $items->count() > 0)
                @foreach($items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $item->order->buyer->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $item->quantity ?? 1 }}</td>
                    <td class="p-2">UGX {{ number_format($item->subtotal ?? 0) }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-white 
                            @if($item->status === 'completed') bg-green-600 
                            @elseif($item->status === 'cancelled') bg-red-600 
                            @else bg-yellow-500 @endif">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="p-2 flex gap-2">
                        @if($item->status === 'pending')
                            <button wire:click="confirmAction({{ $item->id }}, 'complete')"
                                    class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                Complete
                            </button>
                            <button wire:click="confirmAction({{ $item->id }}, 'cancel')"
                                    class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                Cancel
                            </button>
                        @else
                            <button disabled
                                    class="bg-gray-300 text-gray-600 px-2 py-1 rounded cursor-not-allowed">
                                Complete
                            </button>
                            <button disabled
                                    class="bg-gray-300 text-gray-600 px-2 py-1 rounded cursor-not-allowed">
                                Cancel
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        No orders found.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Confirmation Modal -->
    @if($confirmingOrderId)
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">
                Confirm {{ ucfirst($confirmingAction) }} Order
            </h3>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to {{ $confirmingAction }} this order?
            </p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $confirmingAction === 'cancel' ? 'Cancellation Reason' : 'Completion Note' }}
                </label>
                <textarea wire:model.defer="actionReason"
                          rows="3"
                          class="w-full rounded border-gray-300 focus:border-green-500 focus:ring-green-500"
                          placeholder="Enter reason to include in buyer email notification..."></textarea>
                @error('actionReason')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end gap-3">
                <button wire:click="cancelActionConfirmation"
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button wire:click="executeAction" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Yes, {{ ucfirst($confirmingAction) }}
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:toast.window="message = $event.detail.message; type = $event.detail.type; show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg text-white"
        :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'">
        <span x-text="message"></span>
    </div>
</div>
