<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'access_id',
        'access_pass',
        'total_charge',
        'status',
        'order_id',
        'job_cd',
        'secure',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_orders', 'order_id', 'product_id');
    }
}
