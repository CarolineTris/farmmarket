<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BuyerOrders extends Component
{
    use WithPagination;

    // Listen for order updates from both sides
    protected $listeners = [
        'order-updated' => '$refresh',
        'order-updated-buyer' => '$refresh'
    ];

    public function getOrdersProperty()
    {
        return Auth::user()
            ->buyerOrders()
            ->with('items.product.farmer')
            ->latest()
            ->paginate(10);
    }

    public function getTotalOrdersProperty()
    {
        return Auth::user()->buyerOrders()->count();
    }

    public function getPendingOrdersProperty()
    {
        return Auth::user()->buyerOrders()
            ->where('status', 'pending')
            ->count();
    }

    public function getDeliveredOrdersProperty()
    {
        return Auth::user()->buyerOrders()
            ->where('status', 'completed')
            ->count();
    }

    public function getActiveOrdersProperty()
    {
        return Auth::user()->buyerOrders()
            ->whereIn('status', ['pending', 'shipped'])
            ->count();
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'pending_payment'])
            ->first();

        if ($order) {
            $order->update(['status' => 'cancelled']);
            
            // Also cancel all order items
            $order->items()->update(['status' => 'cancelled']);
            
            // Dispatch events to refresh both sides
            $this->dispatch('order-updated');
            $this->dispatch('order-updated-buyer');
            
            session()->flash('success', 'Order cancelled successfully.');
        }
    }

    public function cancelItem($itemId)
    {
        $item = OrderItem::where('id', $itemId)
            ->whereHas('order', fn ($q) =>
                $q->where('buyer_id', auth()->id())
            )
            ->firstOrFail();

        $item->update(['status' => 'cancelled']);

        $item->order->syncStatus();

        // Dispatch events to refresh both sides
        $this->dispatch('order-updated');
        $this->dispatch('order-updated-buyer');

        session()->flash('success', 'Item cancelled successfully');
    }

    public function markAsReceived($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->where('status', 'shipped')
            ->first();

        if ($order) {
            $order->update(['status' => 'delivered']);
            
            // Dispatch events to refresh both sides
            $this->dispatch('order-updated');
            $this->dispatch('order-updated-buyer');
            
            session()->flash('success', 'Order marked as received. Thank you!');
        }
    }

    public function render()
    {
        return view('livewire.buyer-orders', [
            'orders' => $this->orders,
            'totalOrders' => $this->totalOrders,
            'pendingOrders' => $this->pendingOrders,
            'deliveredOrders' => $this->deliveredOrders,
            'activeOrders' => $this->activeOrders,
        ]);
    }
}
