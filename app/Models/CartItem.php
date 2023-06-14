<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $primaryKey = 'id';
    protected $fillable = ['stock_id', 'quantity'];
    public $timestamps = false;

    public function stock()
    {
        return $this->belongsTo(Stocks::class, 'stock_id');
    }
}
