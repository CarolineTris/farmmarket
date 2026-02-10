<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Checkout extends Component
{
    public $cartItems;
    public $total = 0;
    public $mobileProvider = 'mtn';
    public $phone = '';

    public function mount()
    {
        $this->cartItems = CartItem::where('user_id', Auth::id())
            ->with('product.farmer')
            ->get();

        if ($this->cartItems->isEmpty()) {
            return redirect()
                ->route('marketplace')
                ->with('error', 'Your cart is empty.');
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    public function placeOrder()
    {
        $this->validate([
            'mobileProvider' => 'required|in:mtn,airtel',
            'phone' => 'required|string|min:9|max:20',
        ]);

        $order = null;

        DB::transaction(function () use (&$order) {

            $order = Order::create([
                'buyer_id'     => Auth::id(),
                'total_amount' => $this->total,
                'status'       => 'pending_payment',
                'payment_status' => 'pending',
                'payment_provider' => 'flutterwave',
                'currency' => 'UGX',
                'payer_phone' => $this->phone,
                'payer_network' => $this->mobileProvider,
            ]);

            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'product_id'=> $item->product_id,
                    'farmer_id' => $item->product->farmer_id,
                    'quantity'  => $item->quantity,
                    'price'     => $item->product->price,
                    'subtotal'  => $item->quantity * $item->product->price,
                    'status'    => 'pending',
                ]);
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('payments.flutterwave.init', $order);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
