<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class FarmerDashboard extends Component
{
    public $totalListings;
    public $totalPurchases;
    public $completedSales;
    public $earnings;
    public $recentOrders;
    public $topProducts;

    // Listen for order updates
    protected $listeners = ['order-updated' => 'refreshStats'];

    public function refreshStats()
    {
        $farmer = auth()->user();

        $this->totalListings  = $farmer->products()->count();
        $this->totalPurchases = OrderItem::where('farmer_id', $farmer->id)
            ->where('status', 'pending')
            ->count();
        $this->completedSales = OrderItem::where('farmer_id', $farmer->id)
            ->where('status', 'completed')
            ->count();
            
        $this->earnings = OrderItem::where('farmer_id', $farmer->id)
            ->where('status', 'completed')
            ->sum('subtotal');
    }

    public function mount()
    {
        $this->refreshStats();
        
        $farmer = auth()->user();
        
        $this->recentOrders = Order::whereHas('items', function ($query) use ($farmer) {
                $query->where('farmer_id', $farmer->id);
            })
            ->with(['items.product', 'buyer'])
            ->latest()
            ->take(5)
            ->get();
            
        $this->topProducts = OrderItem::where('farmer_id', $farmer->id)
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->with('product')
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.farmer-dashboard');
    }
}
