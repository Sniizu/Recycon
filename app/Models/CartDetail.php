<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;
    public function carts(){
        return $this->belongsTo(Carts::class,'cart_id');
    }
    public function item(){
        return $this->belongsTo(Item::class,'item_id');
    }
}
