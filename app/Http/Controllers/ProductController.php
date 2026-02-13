<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => ['required', Rule::in(array_keys(config('product_categories.list', [])))],
            'unit' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'price', 'quantity', 'description', 'category', 'unit');
        $data['farmer_id'] = auth()->id();

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('products', 'public');
            }
            $data['additional_images'] = json_encode($paths);
        }

        Product::create($data);

        return redirect()->route('farmer.listings')->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => ['required', Rule::in(array_keys(config('product_categories.list', [])))],
            'unit' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:1000',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name', 'price', 'quantity', 'description', 'category', 'unit');

        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('products', 'public');
            }
            $data['additional_images'] = json_encode($paths);
        }

        $product->update($data);

        return redirect()->route('farmer.listings')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('farmer.listings')->with('success', 'Product deleted successfully!');

    }
}
