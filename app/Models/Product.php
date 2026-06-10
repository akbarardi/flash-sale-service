<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuids, HasFactory;

    protected $hidden = ['id'];
    protected $fillable = [
        'name',
        'price',
        'stock',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
