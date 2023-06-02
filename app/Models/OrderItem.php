<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total',
        'price',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
