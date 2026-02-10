<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\WishlistItem;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Wishlist extends Component
{
    protected $listeners = ['wishlist-updated' => '$refresh'];

    public function getWishlistItemsProperty()
    {
        return auth()->user()->wishlistProducts()
            ->with('farmer')
            ->get();
    }

    public function addToWishlist($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Please login to add items to wishlist'
            );
            return;
        }

        $product = Product::find($productId);
        
        if (!$product) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Product not found'
            );
            return;
        }

        // Check if already in wishlist
        if (auth()->user()->wishlistProducts()->where('product_id', $productId)->exists()) {
            $this->dispatch('show-toast', 
                type: 'info', 
                message: 'Product already in wishlist'
            );
            return;
        }

        // Add to wishlist
        auth()->user()->wishlistProducts()->attach($productId);
        
        $this->dispatch('wishlist-updated');
        $this->dispatch('show-toast', 
            type: 'success', 
            message: 'Added to wishlist!'
        );
    }

    public function removeFromWishlist($productId)
    {
        auth()->user()->wishlistProducts()->detach($productId);
        
        $this->dispatch('wishlist-updated');
        $this->dispatch('show-toast', 
            type: 'info', 
            message: 'Removed from wishlist'
        );
    }

    public function moveToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Product not found'
            );
            return;
        }

        // Remove from wishlist
        $this->removeFromWishlist($productId);
        
        // Add to cart (using session-based cart)
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'farmer_id' => $product->farmer_id,
                'farmer_name' => $product->farmer->name,
            ];
        }
        
        session()->put('cart', $cart);
        
        // Notify cart component
        $this->dispatch('cart-updated');
        
        $this->dispatch('show-toast', 
            type: 'success', 
            message: 'Moved to cart!'
        );
    }

    public function clearWishlist()
    {
        auth()->user()->wishlistProducts()->detach();
        
        $this->dispatch('wishlist-updated');
        $this->dispatch('show-toast', 
            type: 'info', 
            message: 'Wishlist cleared'
        );
    }

    public function render()
    {
        return view('livewire.wishlist', [
            'wishlistItems' => $this->wishlistItems
        ]);
    }
}