<?php

namespace App\Models;

use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
    ];

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction): void {
            if (empty($transaction->invoice_number)) {
                $transaction->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $count = static::query()
            ->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->count() + 1;

        return Str::upper(sprintf('INV-%s-%04d', $date, $count));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
