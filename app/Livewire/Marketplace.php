<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

use Livewire\Attributes\Layout;

#[Layout('layouts.app')]


class Marketplace extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $category = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Available categories
    public $categories = [];

    // Available sorting options
    public $sortOptions = [
        'created_at' => 'Newest',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'name_asc' => 'Name: A to Z',
        'name_desc' => 'Name: Z to A',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->categories = config('product_categories.list', []);
    }

    public function updated($property)
    {
        // Reset to first page when filters change
        if (in_array($property, ['search', 'category', 'minPrice', 'maxPrice', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'minPrice', 'maxPrice']);
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        $product = Product::with('farmer')->find($productId);

        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }

        if ($product->quantity < 1) {
            session()->flash('error', 'Product is out of stock!');
            return;
        }

        if (Auth::check()) {
            // ✅ DATABASE CART FOR LOGGED-IN USERS
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                if ($cartItem->quantity + 1 > $product->quantity) {
                    session()->flash('error', 'Cannot add more than available stock!');
                    return;
                }

                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => 1,
                    'name' => $product->name,
                    'price' => $product->price,
                ]);
            }
        } else {
            // ✅ SESSION CART FOR GUESTS
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
                    'unit' => $product->unit,
                ];
            }

            session()->put('cart', $cart);
        }

        // Refresh cart everywhere
        $this->dispatch('cart-updated');

        $this->dispatch('show-toast',
            type: 'success',
            message: $product->name . ' added to cart!'
        );
    }

    public function viewProduct($productId)
    {
        return redirect()->route('products.show', $productId);
    }

    public function getProductsProperty()
    {
        $query = Product::with(['farmer'])
            ->where('quantity', '>', 0) // Only show products in stock
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('farmer', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->minPrice, function ($query) {
                $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            });

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', $this->sortDirection);
                break;
        }

        return $query->paginate(12);
    }
    // In Marketplace.php component
    public function addToWishlist($productId)
    {
        if (!auth()->check()) {
            session()->flash('error', 'Please login to add to wishlist');
            return;
        }

        $product = Product::find($productId);
        
        if (!$product) {
            session()->flash('error', 'Product not found');
            return;
        }

        // Check if already in wishlist
        if (auth()->user()->wishlistProducts()->where('product_id', $productId)->exists()) {
            session()->flash('info', 'Already in wishlist');
            return;
        }

        // Add to wishlist
        auth()->user()->wishlistProducts()->attach($productId);
        
        session()->flash('success', 'Added to wishlist!');
    }

    public function isInWishlist($productId)
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->wishlistProducts()
            ->where('product_id', $productId)
            ->exists();
    }

        public function getFeaturedFarmers()
    {
        // Return some featured farmers
        return \App\Models\User::where('role', 'farmer')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(3)
            ->get()
            ->map(function ($farmer) {
                return (object) [
                    'id' => $farmer->id,
                    'name' => $farmer->name,
                    'products_count' => $farmer->products_count,
                    'bio' => $farmer->bio ?? 'Experienced local farmer',
                ];
            });
    }

    public function render()
    {
        return view('livewire.marketplace', [
            'products' => $this->products,
        ]);
    }
}
