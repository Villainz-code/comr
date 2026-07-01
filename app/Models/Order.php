<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'selected_size',
        'total_price',
        'shipping_address',
        'recipient_name',
        'phone',
        'email',
        'city',
        'country',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_channel',
        'payment_code',
        'payment_deadline',
        'shipping_note',
        'status',
        'estimated_arrival',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'payment_deadline' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
