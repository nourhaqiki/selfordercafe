<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class CafeTable extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $fillable = [
        'table_number',
        'capacity',
        'qr_payload',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public static function signedOrderUrl(string $tableNumber): string
    {
        return URL::signedRoute('customer.catalog', ['table' => $tableNumber]);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'table_number', 'table_number');
    }

    public function activeOrder()
    {
        return $this->hasOne(Order::class, 'table_number', 'table_number')
            ->whereIn('status', ['PENDING_PAYMENT', 'PAID', 'PREPARING'])
            ->latest();
    }
}

