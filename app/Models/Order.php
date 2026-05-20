<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'customer_name', 'customer_phone',
        'customer_address', 'customer_note', 'total_amount', 'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public static function generateOrderCode(): string
    {
        $latest = self::latest()->first();
        $number = $latest ? intval(substr($latest->order_code, 4)) + 1 : 1;
        return 'SYM-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
