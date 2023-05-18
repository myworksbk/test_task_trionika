<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'user_id',
        'quantity',
        'price',
        'status'
    ];

    protected $appends = ['sum'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSumAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    public function scopeAuth($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
