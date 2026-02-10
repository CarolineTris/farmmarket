<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;  // Changed from cart to CartItem
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Cart extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];
    
    public $cartItems = [];  // Fixed: Changed from $cart to $cartItems
    public $total = 0;
    
    public function mount()
    {
        $this->loadCart();
    }
    
    public function loadCart()
    {
        if (Auth::check()) {
            // Load from database for logged-in users
            $this->cartItems = \App\Models\CartItem::where('user_id', Auth::id())
                ->with('product.farmer')
                ->get()
                ->keyBy('product_id')
                ->map(function ($cartItem) {
                    return [
                        'cart_item_id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'name' => $cartItem->product->name,
                        'price' => $cartItem->product->price,
                        'quantity' => $cartItem->quantity,
                        'image' => $cartItem->product->image,
                        'farmer_id' => $cartItem->product->farmer_id,
                        'farmer_name' => $cartItem->product->farmer->name,
                        'unit' => $cartItem->product->unit,
                    ];
                })
                ->toArray();
        } else {
            // Load from session for guests
            $this->cartItems = session()->get('cart', []);
        }
        
        $this->calculateTotal();
    }
    
    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::with('farmer')->find($productId);
        
        if (!$product) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Product not found!'
            );
            return;
        }
        
        if ($product->quantity < $quantity) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Not enough stock available!'
            );
            return;
        }
        
        if (Auth::check()) {
            // Database cart for logged-in users
            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                ],
                [
                    'quantity' => \DB::raw("quantity + $quantity"),
                ]
            );
        } else {
            // Session cart for guests
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image,
                    'farmer_id' => $product->farmer_id,
                    'farmer_name' => $product->farmer->name,
                    'unit' => $product->unit,
                ];
            }
            
            session()->put('cart', $cart);
        }
        
        $this->dispatch('cart-updated');
        $this->dispatch('show-toast', 
            type: 'success', 
            message: $product->name . ' added to cart!'
        );
    }
    
    public function updateQuantity($productId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeItem($productId);
            return;
        }
        
        if (Auth::check()) {
            // Update in database
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        } else {
            // Update in session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
        }
        
        $this->loadCart();
    }
    
    public function removeItem($productId)
    {
        if (Auth::check()) {
            // Remove from database
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            // Remove from session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
        }
        
        $this->loadCart();
        $this->dispatch('show-toast', 
            type: 'info', 
            message: 'Item removed from cart!'
        );
    }
    
    public function clearCart()
    {
        if (Auth::check()) {
            // Clear database cart
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            // Clear session cart
            session()->forget('cart');
        }
        
        $this->loadCart();
        $this->dispatch('show-toast', 
            type: 'info', 
            message: 'Cart cleared!'
        );
    }
    
    public function calculateTotal()
    {
        $this->total = 0;
        foreach ($this->cartItems as $item) {
            $this->total += $item['price'] * $item['quantity'];
        }
    }
    
    public function checkout()
    {
        if (empty($this->cartItems)) {
            $this->dispatch('show-toast', 
                type: 'error', 
                message: 'Your cart is empty!'
            );
            return;
        }
        
        // Redirect to checkout page
        return redirect()->route('checkout');
    }
    
    public function render()
    {
        return view('livewire.cart');
    }
}