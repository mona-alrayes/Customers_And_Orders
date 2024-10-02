<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory , SoftDeletes;

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

    public function getStatusAttribute(string $value): string
    {
      if ($value === '0') {
          return 'pending';
      }
      if ($value === '1') {
          return 'completed';
      }
    }
    public function setStatusAttribute(string $value): void{
        if($value === 'pending'){
            $this->attributes['status'] = '0';
        }
        elseif($value === 'completed') {
            $this->attributes['status'] = '1';
        }
    }
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
     return $this->belongsTo(Customer::class);
    }
}
