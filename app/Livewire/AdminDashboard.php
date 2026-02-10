<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalFarmers;
    public $totalBuyers;
    public $totalProducts;
    public $totalOrders;
    public $totalRevenue;
    public $pendingOrders;
    public $activeProducts;
    public $pendingVerifications;
    
    public $recentOrders;
    public $recentFarmers;
    public $recentProducts;
    
    public $platformStats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // User Statistics
        $this->totalUsers = User::count();
        $this->totalFarmers = User::where('role', 'farmer')->count();
        $this->totalBuyers = User::where('role', 'buyer')->count();
        
        // Product Statistics
        $this->totalProducts = Product::count();
        $this->activeProducts = Product::where('quantity', '>', 0)->count();
        
        // Order Statistics
        $this->totalOrders = Order::count();
        $this->pendingOrders = Order::where('status', 'pending')->count();
        
        // Revenue
        $this->totalRevenue = Order::where('status', 'delivered')->sum('total_amount') ?? 0;
        
        // FIXED: Use verification_status instead of is_verified
        $this->pendingVerifications = User::where('role', 'farmer')
            ->where('verification_status', 'pending')
            ->count();
        
        // Recent Data
        $this->recentOrders = Order::with(['buyer', 'items.farmer'])
            ->latest()
            ->take(5)
            ->get();
            
        // FIXED: Get pending farmers using verification_status
        $this->recentFarmers = User::where('role', 'farmer')
            ->where('verification_status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        $this->recentProducts = Product::with('farmer')
            ->latest()
            ->take(5)
            ->get();
        
        // Platform Statistics
        $this->platformStats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'products_added_today' => Product::whereDate('created_at', today())->count(),
            'pending_farmers' => $this->pendingVerifications,
        ];
    }

    public function refreshStats()
    {
        $this->loadStats();
        $this->dispatch('stats-refreshed');
    }

    // Add method to verify farmers
    public function verifyFarmer($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->role === 'farmer' && $user->verification_status === 'pending') {
            $user->update([
                'verification_status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
            
            session()->flash('success', 'Farmer verified successfully!');
            $this->loadStats(); // Refresh the stats
        }
    }

    // Add method to reject farmers
    public function rejectFarmer($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->role === 'farmer' && $user->verification_status === 'pending') {
            $user->update([
                'verification_status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);
            
            session()->flash('success', 'Farmer rejected successfully!');
            $this->loadStats(); // Refresh the stats
        }
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
            
}
