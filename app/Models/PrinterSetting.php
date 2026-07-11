<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    protected $fillable = [
        'printer_name',
        'printer_address',
        'connection_type',
        'paper_size',
        'auto_print',
        'print_customer_copy',
        'print_kitchen_copy',
    ];

    protected function casts(): array
    {
        return [
            'paper_size' => 'integer',
            'auto_print' => 'boolean',
            'print_customer_copy' => 'boolean',
            'print_kitchen_copy' => 'boolean',
        ];
    }
}
