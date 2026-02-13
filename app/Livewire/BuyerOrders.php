<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\BuyerOrderStatusNotification;
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
            ->whereIn('status', ['pending', 'pending_payment'])
            ->count();
    }

    public function getActiveOrdersProperty()
    {
        return Auth::user()->buyerOrders()
            ->whereIn('status', ['pending', 'pending_payment'])
            ->count();
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('buyer_id', Auth::id())
            ->whereIn('status', ['pending', 'pending_payment'])
            ->first();

        if ($order) {
            $reason = 'Order cancelled by buyer request.';

            $order->update([
                'status' => 'cancelled',
                'status_reason' => $reason,
            ]);
            
            // Also cancel all order items
            $order->items()->update([
                'status' => 'cancelled',
                'status_reason' => $reason,
            ]);

            rescue(
                fn () => Auth::user()?->notify(
                    new BuyerOrderStatusNotification($order, 'cancelled', $reason, 'Buyer')
                ),
                report: false
            );
            
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

        $reason = 'Item cancelled by buyer request.';
        $item->update([
            'status' => 'cancelled',
            'status_reason' => $reason,
        ]);

        $previousOrderStatus = $item->order->status;
        $item->order->syncStatus();

        $order = $item->order->refresh();
        if ($order->status === 'cancelled' && $order->status !== $previousOrderStatus) {
            $order->update(['status_reason' => $reason]);

            rescue(
                fn () => Auth::user()?->notify(
                    new BuyerOrderStatusNotification($order, 'cancelled', $reason, 'Buyer')
                ),
                report: false
            );
        }

        // Dispatch events to refresh both sides
        $this->dispatch('order-updated');
        $this->dispatch('order-updated-buyer');

        session()->flash('success', 'Item cancelled successfully');
    }

    public function render()
    {
        return view('livewire.buyer-orders', [
            'orders' => $this->orders,
            'totalOrders' => $this->totalOrders,
            'pendingOrders' => $this->pendingOrders,
            'activeOrders' => $this->activeOrders,
        ]);
    }
}
