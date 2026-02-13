<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\BuyerOrderStatusNotification;
use App\Notifications\FarmerNewPaidOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function initFlutterwave(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403);
        }

        if (!config('services.flutterwave.secret_key')) {
            return redirect()
                ->route('checkout')
                ->with('error', 'Payment gateway is not configured yet.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('buyer.orders')
                ->with('success', 'Order already paid.');
        }

        $txRef = $order->payment_tx_ref ?: ('FM-' . $order->id . '-' . now()->timestamp);
        $order->update([
            'payment_provider' => 'flutterwave',
            'payment_tx_ref' => $txRef,
            'currency' => 'UGX',
        ]);

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->post('https://api.flutterwave.com/v3/payments', [
                'tx_ref' => $txRef,
                'amount' => $order->total_amount,
                'currency' => 'UGX',
                'redirect_url' => URL::route('payments.flutterwave.callback'),
                'customer' => [
                    'email' => $order->buyer->email ?? 'buyer@example.com',
                    'name' => $order->buyer->name ?? 'Buyer',
                    'phonenumber' => $order->payer_phone,
                ],
                'payment_options' => 'mobilemoneyuganda',
                'meta' => [
                    'order_id' => $order->id,
                    'network' => $order->payer_network,
                ],
            ]);

        if (!$response->ok()) {
            Log::error('Flutterwave init failed', ['body' => $response->body()]);
            return redirect()
                ->route('checkout')
                ->with('error', 'Failed to start payment. Please try again.');
        }

        $data = $response->json();
        $paymentLink = $data['data']['link'] ?? null;

        if (!$paymentLink) {
            return redirect()
                ->route('checkout')
                ->with('error', 'Failed to start payment. Please try again.');
        }

        return redirect()->away($paymentLink);
    }

    public function flutterwaveCallback(Request $request)
    {
        $status = $request->query('status');
        $txId = $request->query('transaction_id');
        $txRef = $request->query('tx_ref');

        if (!$txId && !$txRef) {
            return redirect()->route('buyer.orders')->with('error', 'Payment could not be verified.');
        }

        if (!config('services.flutterwave.secret_key')) {
            return redirect()->route('buyer.orders')->with('error', 'Payment gateway is not configured yet.');
        }

        $verifyUrl = $txId
            ? "https://api.flutterwave.com/v3/transactions/{$txId}/verify"
            : "https://api.flutterwave.com/v3/transactions/verify_by_reference?tx_ref={$txRef}";

        $response = Http::withToken(config('services.flutterwave.secret_key'))
            ->get($verifyUrl);

        if (!$response->ok()) {
            return redirect()->route('buyer.orders')->with('error', 'Payment verification failed.');
        }

        $data = $response->json('data');
        $orderId = $data['meta']['order_id'] ?? null;

        if (!$orderId) {
            return redirect()->route('buyer.orders')->with('error', 'Payment verification failed.');
        }

        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('buyer.orders')->with('error', 'Order not found.');
        }

        if (($data['status'] ?? '') === 'successful') {
            $order->update([
                'payment_status' => 'paid',
                'payment_reference' => $data['flw_ref'] ?? $txId,
                'status' => 'pending',
                'status_reason' => 'Payment confirmed. Order is pending farmer fulfillment.',
                'paid_at' => now(),
            ]);

            rescue(
                fn () => optional($order->buyer)->notify(
                    new BuyerOrderStatusNotification(
                        $order,
                        'pending',
                        'Payment received successfully. Farmers can now fulfill your order.',
                        'System'
                    )
                ),
                report: false
            );

            $order->loadMissing('items.farmer');
            $order->items
                ->groupBy('farmer_id')
                ->each(function ($items) use ($order) {
                    $farmer = $items->first()?->farmer;

                    rescue(
                        fn () => optional($farmer)->notify(
                            new FarmerNewPaidOrderNotification($order, $items->count())
                        ),
                        report: false
                    );
                });

            return redirect()->route('buyer.orders')->with('success', 'Payment successful. Your order is now pending fulfillment.');
        }

        $order->update([
            'payment_status' => 'failed',
            'status_reason' => 'Payment failed or was cancelled at the payment gateway.',
        ]);

        rescue(
            fn () => optional($order->buyer)->notify(
                new BuyerOrderStatusNotification(
                    $order,
                    'payment_failed',
                    'Payment failed. Please retry payment to continue with this order.',
                    'System'
                )
            ),
            report: false
        );

        return redirect()->route('buyer.orders')->with('error', 'Payment was not successful.');
    }
}
