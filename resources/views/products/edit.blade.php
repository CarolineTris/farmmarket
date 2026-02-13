@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Edit Product</h2>
        <a href="{{ route('farmer.listings') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-arrow-left mr-2 text-xs"></i> Back to Listings
        </a>
    </div>

    <div class="p-6">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid gap-6">

                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm
                                  placeholder-gray-400
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  hover:border-gray-400 sm:text-sm" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3"
                              class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm
                                     placeholder-gray-400
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                     hover:border-gray-400 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Quantity (two-column on md+) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category"
                                class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       hover:border-gray-400 sm:text-sm"
                                required>
                            <option value="">Select a category</option>
                            @foreach(config('product_categories.list', []) as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $product->category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price (UGX)</label>
                        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm
                                      placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      hover:border-gray-400 sm:text-sm" required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                               class="mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm
                                      placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      hover:border-gray-400 sm:text-sm" required>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" name="image" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-600
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($product->image)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-1">Current Image:</p>
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="Product Image"
                                 class="h-32 rounded-lg shadow border border-gray-200 object-cover">
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('farmer.listings') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition">
                        <i class="fas fa-save mr-2 text-sm"></i> Update Product
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
