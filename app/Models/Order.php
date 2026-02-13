<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'buyer_id',
        'total_amount',
        'status', // e.g. pending, completed, cancelled
        'status_reason',
        'payment_status', // pending, paid, failed
        'payment_provider',
        'payment_reference',
        'payment_tx_ref',
        'currency',
        'payer_phone',
        'payer_network',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relationships

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

  public function syncStatus()
{
    $totalItems = $this->items()->count();

    if ($totalItems === 0) {
        return;
    }

    if ($this->items()->where('status', 'pending')->exists()) {
        $this->update(['status' => 'pending']);
        return;
    }

    $cancelledCount = $this->items()->where('status', 'cancelled')->count();
    if ($cancelledCount === $totalItems) {
        $this->update(['status' => 'cancelled']);
        return;
    }

    $completedOrCancelledCount = $this->items()
        ->whereIn('status', ['completed', 'cancelled'])
        ->count();
    if ($completedOrCancelledCount === $totalItems) {
        $this->update(['status' => 'completed']);
        return;
    }

    if ($this->items()->where('status', 'completed')->count() === $totalItems) {
        $this->update(['status' => 'completed']);
        return;
    }
}

    public function getComputedStatusAttribute()
    {
        // Normalize legacy terminal status to current canonical status.
        if ($this->status === 'delivered') {
            return 'completed';
        }

        // For pay-on-delivery orders, pending payment should still be treated as pending fulfillment.
        if (
            $this->status === 'pending_payment' &&
            in_array($this->payment_provider, ['cash_on_delivery', 'mobile_money_on_delivery'], true)
        ) {
            return 'pending';
        }

        if ($this->payment_status !== 'paid' && $this->status === 'pending_payment') {
            return 'pending_payment';
        }

        $items = $this->relationLoaded('items')
            ? $this->items
            : $this->items()->get();

        if ($items->isEmpty()) {
            return $this->status ?? 'pending';
        }

        // Treat missing status as pending
        if ($items->contains(fn ($item) => $item->status === 'pending' || $item->status === null)) {
            return 'pending';
        }

        if ($items->every(fn ($item) => $item->status === 'cancelled')) {
            return 'cancelled';
        }

        if ($items->every(fn ($item) => in_array($item->status, ['completed', 'cancelled'], true))) {
            return 'completed';
        }

        if ($items->every(fn ($item) => $item->status === 'completed')) {
            return 'completed';
        }

        return $this->status ?? 'pending';
    }



}
