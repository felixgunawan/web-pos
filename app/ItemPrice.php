<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    //
    protected $table = 'item_prices';
    protected $fillable = ['item_id','buy_price','sell_price'];
}
