<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = ['item_id','location_id','first_stock','in_stock','out_stock','now_stock'];

    
}
