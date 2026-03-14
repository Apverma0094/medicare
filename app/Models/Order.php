<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'tax_amount',
        'grand_total',
        'status',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'delivery_address',
        'notes',
        'order_date',
        'delivery_date',
        'cancellation_reason',
        'cancelled_at',
        'refund_status',
        'refund_amount',
        'refund_requested_at',
        'refund_processed_at',
        'refund_notes',
        'refund_reference'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'delivery_address' => 'array',
        'order_date' => 'datetime',
        'delivery_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'MS' . date('Ymd');
        $lastOrder = self::whereDate('created_at', today())
                        ->where('order_number', 'like', $prefix . '%')
                        ->orderBy('order_number', 'desc')
                        ->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $newNumber;
    }
}
