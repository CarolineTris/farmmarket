<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Orders extends Component
{
    public $items;
    public $confirmingOrderId= null;
    public $confirmingAction = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->items = OrderItem::where('farmer_id', auth()->id())
            ->with(['product', 'order.buyer'])
            ->latest()
            ->get();
    }

    public function confirmAction($itemId, $action)
    {
        $this->confirmingOrderId = $itemId;
        $this->confirmingAction = $action;
    }

    
    public function executeAction()
    {
        $item = OrderItem::where('id', $this->confirmingOrderId)
            ->where('farmer_id', auth()->id())
            ->firstOrFail();

        if ($this->confirmingAction === 'complete' && $item->status === 'pending') {
            $item->update(['status' => 'completed']);

            // reduce stock
            $item->product->decrement('quantity', $item->quantity);
        }

        if ($this->confirmingAction === 'cancel' && $item->status !== 'cancelled') {
            $item->update(['status' => 'cancelled']);
        }

        // 🔥 sync parent order
        $item->order->syncStatus();

        $this->reset(['confirmingOrderId', 'confirmingAction']);
        $this->loadOrders();

        // refresh both dashboards/views
        $this->dispatch('order-updated');
        $this->dispatch('order-updated-buyer');

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Order item updated successfully',
        ]);
    }

    private function updateOrderStatusIfAllCompleted($orderId)
    {
        $order = Order::with('items')->find($orderId);
        
        // Check if all items are either completed or cancelled
        $allItemsCompleted = $order->items->every(function ($item) {
            return in_array($item->status, ['completed', 'cancelled']);
        });
        
        if ($allItemsCompleted) {
            $order->update(['status' => 'completed']);
        }
    }

    private function updateOrderStatusIfAllCancelled($orderId)
    {
        $order = Order::with('items')->find($orderId);
        
        // Check if all items are cancelled
        $allItemsCancelled = $order->items->every(function ($item) {
            return $item->status === 'cancelled';
        });
        
        if ($allItemsCancelled) {
            $order->update(['status' => 'cancelled']);
        }
    }

    public function completeItem($itemId)
    {
        $item = OrderItem::where('id', $itemId)
            ->where('farmer_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($item) {

            // Reduce stock
            $item->product->decrement('quantity', $item->quantity);

            // Mark item completed
            $item->update(['status' => 'completed']);

            // Update parent order status
            $item->order->syncStatus();
        });

        session()->flash('success', 'Order item completed');
    }

    public function cancelItem($itemId)
    {
        $item = OrderItem::findOrFail($itemId);

        // Authorization check
        if (
            auth()->id() !== $item->farmer_id &&
            auth()->id() !== $item->order->buyer_id
        ) {
            abort(403);
        }

        $item->update(['status' => 'cancelled']);

        $item->order->syncStatus();

        session()->flash('success', 'Order item cancelled');
    }


    public function render()
    {
        return view('livewire.orders');
    }
}
