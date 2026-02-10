<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-green-800">Checkout</h2>

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    @foreach($cartItems as $item)
        <div class="flex justify-between border-b py-3">
            <div>
                <p class="font-medium">{{ $item->product->name }}</p>
                <p class="text-sm text-gray-500">
                    {{ $item->quantity }} × UGX {{ number_format($item->product->price) }}
                </p>
            </div>
            <span class="font-semibold">
                UGX {{ number_format($item->quantity * $item->product->price) }}
            </span>
        </div>
    @endforeach

    <div class="mt-6 text-right font-bold text-xl">
        Total: UGX {{ number_format($total) }}
    </div>

    <div class="mt-6 space-y-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">Mobile Money Provider</label>
            <select wire:model="mobileProvider"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="mtn">MTN Mobile Money</option>
                <option value="airtel">Airtel Money</option>
            </select>
            @error('mobileProvider') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Mobile Money Number</label>
            <input type="text" wire:model="phone"
                placeholder="e.g. 2567XXXXXXXX"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <button wire:click="placeOrder"
        class="mt-6 w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition">
        Pay with Mobile Money
    </button>
</div>
