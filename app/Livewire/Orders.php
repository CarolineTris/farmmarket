<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\BuyerOrderStatusNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Orders extends Component
{
    public $items;
    public $confirmingOrderId = null;
    public $confirmingAction = null;
    public $actionReason = '';

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
        $this->actionReason = $action === 'cancel'
            ? 'Cancelled by farmer due to product availability or delivery issues.'
            : 'Completed by farmer. Item was fulfilled successfully.';
    }

    public function cancelActionConfirmation()
    {
        $this->reset(['confirmingOrderId', 'confirmingAction', 'actionReason']);
    }

    public function executeAction()
    {
        $this->validate([
            'actionReason' => 'required|string|min:5|max:500',
        ]);

        $item = OrderItem::where('id', $this->confirmingOrderId)
            ->where('farmer_id', auth()->id())
            ->with(['order.buyer', 'product'])
            ->firstOrFail();

        $reason = trim($this->actionReason);
        $order = $item->order;
        $previousOrderStatus = $order->status;

        if ($this->confirmingAction === 'complete' && $item->status === 'pending') {
            $item->update([
                'status' => 'completed',
                'status_reason' => $reason,
            ]);

            $item->product->decrement('quantity', $item->quantity);
        }

        if ($this->confirmingAction === 'cancel' && $item->status !== 'cancelled') {
            $item->update([
                'status' => 'cancelled',
                'status_reason' => $reason,
            ]);
        }

        $order->syncStatus();
        $order->refresh();

        if (in_array($order->status, ['completed', 'cancelled'], true) && $order->status !== $previousOrderStatus) {
            $order->update(['status_reason' => $reason]);

            rescue(
                fn () => optional($order->buyer)->notify(
                    new BuyerOrderStatusNotification($order, $order->status, $reason, auth()->user()?->name)
                ),
                report: false
            );
        }

        $this->reset(['confirmingOrderId', 'confirmingAction', 'actionReason']);
        $this->loadOrders();

        $this->dispatch('order-updated');
        $this->dispatch('order-updated-buyer');

        $this->dispatch('toast', type: 'success', message: 'Order item updated successfully');
    }

    private function updateOrderStatusIfAllCompleted($orderId)
    {
        $order = Order::with('items')->find($orderId);

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
            $item->product->decrement('quantity', $item->quantity);

            $item->update([
                'status' => 'completed',
                'status_reason' => 'Completed by farmer.',
            ]);

            $item->order->syncStatus();
        });

        $order = $item->order()->with('buyer')->first();
        if ($order && $order->status === 'completed') {
            $reason = 'All items were completed by the farmer.';
            $order->update(['status_reason' => $reason]);

            rescue(
                fn () => optional($order->buyer)->notify(
                    new BuyerOrderStatusNotification($order, 'completed', $reason, auth()->user()?->name)
                ),
                report: false
            );
        }

        session()->flash('success', 'Order item completed');
    }

    public function cancelItem($itemId)
    {
        $item = OrderItem::findOrFail($itemId);

        if (
            auth()->id() !== $item->farmer_id &&
            auth()->id() !== $item->order->buyer_id
        ) {
            abort(403);
        }

        $item->update([
            'status' => 'cancelled',
            'status_reason' => 'Cancelled by farmer/admin action.',
        ]);

        $item->order->syncStatus();

        $order = $item->order()->with('buyer')->first();
        if ($order && $order->status === 'cancelled') {
            $reason = 'All items were cancelled for this order.';
            $order->update(['status_reason' => $reason]);

            rescue(
                fn () => optional($order->buyer)->notify(
                    new BuyerOrderStatusNotification($order, 'cancelled', $reason, auth()->user()?->name)
                ),
                report: false
            );
        }

        session()->flash('success', 'Order item cancelled');
    }

    public function render()
    {
        return view('livewire.orders');
    }
}
