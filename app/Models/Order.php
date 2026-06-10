<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids, HasFactory;

    protected $hidden = ['id'];
    protected $fillable = [
        'no_order',
    ];

    public function uniqueIds()
    {
        return ['uuid'];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
