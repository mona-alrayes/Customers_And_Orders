<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory , softDeletes;

    protected $fillable = [
        'customer_id',
        'product_name',
        'quantity',
        'price',
        'status',
        'order_date',
    ];
    protected $casts = [
        'status' => 'boolean',
        'order_date' => 'datetime',
    ];
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
     return $this->belongsTo(Customer::class);
    }
}
