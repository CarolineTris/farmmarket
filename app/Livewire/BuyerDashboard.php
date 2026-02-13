<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BuyerDashboard extends Component
{
    public $totalOrders;
    public $activeOrders;
    public $monthlySpending;
    public $totalSavings;
    public $recentOrders;
    public $recommendedFarmers=[];
    public $seasonalProducts;
    public $topFarmers;

    public function mount()
    {
        $buyerId = Auth::id();

        $this->totalOrders = Order::where('buyer_id', $buyerId)->count();

        $this->activeOrders = OrderItem::whereHas('order', function ($q) use ($buyerId) {
                $q->where('buyer_id', $buyerId);
            })
            ->where('status', 'pending')
            ->distinct('order_id')
            ->count('order_id');

        $this->monthlySpending = OrderItem::where('status', 'completed')
            ->whereHas('order', function ($q) use ($buyerId) {
                $q->where('buyer_id', $buyerId)
                  ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            })
            ->sum('subtotal');

        // FIXED: Use items.product relationship
        $this->recentOrders = Order::with(['items.product', 'items.farmer'])
            ->where('buyer_id', $buyerId)
            ->latest()
            ->take(5)
            ->get();

        // FIXED: Get top farmers from order items
        $this->topFarmers = OrderItem::whereHas('order', function ($q) use ($buyerId) {
                $q->where('buyer_id', $buyerId)
                  ->where('status', 'completed');
            })
            ->with('farmer')
            ->select('farmer_id')
            ->groupBy('farmer_id')
            ->take(6)
            ->get()
            ->map(fn ($item) => $item->farmer)
            ->filter()
            ->values();

        $this->recommendedFarmers = OrderItem::whereHas('order', function ($q) use ($buyerId) {
                $q->where('buyer_id', $buyerId)
                  ->where('status', 'completed');
            })
            ->with('farmer')
            ->select('farmer_id')
            ->groupBy('farmer_id')
            ->take(6)
            ->get()
            ->map(fn ($item) => $item->farmer)
            ->filter()
            ->values();

        $this->seasonalProducts = Product::with('farmer')
            ->latest()
            ->take(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.buyer-dashboard');
    }
}
