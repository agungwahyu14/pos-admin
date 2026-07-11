<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'store_address',
        'phone',
        'currency',
        'tax_enabled',
        'tax_type',
        'tax_value',
        'service_enabled',
        'service_value',
    ];

    protected function casts(): array
    {
        return [
            'tax_enabled' => 'boolean',
            'service_enabled' => 'boolean',
            'tax_value' => 'decimal:2',
            'service_value' => 'decimal:2',
        ];
    }
}
