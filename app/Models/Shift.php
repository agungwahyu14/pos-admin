<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'starting_cash',
        'expected_cash',
        'actual_cash',
        'expected_qris',
        'actual_qris',
        'target_cups',
        'target_foods',
        'actual_cups',
        'actual_foods',
        'close_notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'starting_cash' => 'decimal:2',
            'expected_cash' => 'decimal:2',
            'actual_cash' => 'decimal:2',
            'expected_qris' => 'decimal:2',
            'actual_qris' => 'decimal:2',
        ];
    }

    protected function expectedCash(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (($attributes['status'] ?? null) === 'open') {
                    $cashSales = $this->orders()
                        ->where('status', 'completed')
                        ->where('payment_method', 'cash')
                        ->sum('total_amount');
                    return ($attributes['starting_cash'] ?? 0) + $cashSales;
                }
                return $value;
            }
        );
    }

    protected function expectedQris(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (($attributes['status'] ?? null) === 'open') {
                    return $this->orders()
                        ->where('status', 'completed')
                        ->where('payment_method', 'qris')
                        ->sum('total_amount');
                }
                return $value;
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
