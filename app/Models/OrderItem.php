<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasUuids, HasFactory;

    protected $hidden = ['id'];
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
