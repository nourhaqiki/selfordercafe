<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'PENDING_PAYMENT';
    public const STATUS_PAID = 'PAID';
    public const STATUS_PREPARING = 'PREPARING';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'order_code',
        'table_number',
        'customer_name',
        'status',
        'payment_method',
        'total_amount',
        'notes',
        'paid_at',
        'customer_paid_at',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'customer_paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeForCashier($query)
    {
        return $query->where(function ($q) {
            $q->where('payment_method', 'cash')
              ->orWhereNotNull('customer_paid_at')
              ->orWhereNotNull('paid_at')
              ->orWhereIn('status', [self::STATUS_PAID, self::STATUS_PREPARING, self::STATUS_COMPLETED]);
        });
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Terbayar (Siap Masak)',
            self::STATUS_PREPARING => 'Sedang Dimasak',
            self::STATUS_COMPLETED => 'Selesai Disajikan',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match(strtolower($this->payment_method)) {
            'qris' => 'QRIS (Gopay/OVO/Dana/BCA)',
            'cash' => 'Tunai di Kasir',
            'transfer' => 'Transfer Bank',
            'debit' => 'Kartu Debit / EDC',
            default => strtoupper($this->payment_method),
        };
    }
}

