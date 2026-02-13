@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">

    {{-- Farmer Info --}}
    <div class="bg-white p-6 rounded shadow mb-8">
        <h1 class="text-3xl font-bold text-green-800">{{ $user->name }}</h1>
        @php
            $farmerCategories = collect($user->farmer_categories ?? [])
                ->map(fn ($key) => config("product_categories.list.{$key}", $key))
                ->filter()
                ->values();
        @endphp
        <p class="text-gray-600 mt-2">
            📍 {{ $user->farm_location }}
            @if($farmerCategories->isNotEmpty())
                · 🌱 {{ $farmerCategories->join(', ') }}
            @elseif($user->crops_grown)
                · 🌱 {{ $user->crops_grown }}
            @endif
        </p>

        <span class="inline-block mt-3 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
            ✅ Verified Farmer
        </span>
    </div>

    {{-- Products --}}
    <h2 class="text-2xl font-semibold mb-4">Products</h2>

    @if ($products->isEmpty())
        <p class="text-gray-500">No products listed yet.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="font-semibold">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ config('product_categories.list')[$product->category] ?? 'Uncategorized' }}
                    </p>
                    <p class="text-sm text-gray-600">{{ $product->price }} UGX</p>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
