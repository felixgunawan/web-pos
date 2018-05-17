<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = ['supplier_id','department_id','item_code','name','unit','first_stock','in_stock','out_stock','now_stock','barcode','details','deleted']; 

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function sales()
    {
        return $this->hasMany('App\Sale');
    }
    public function stocks()
    {
        return $this->hasMany('App\Stock');
    }   
    public function itemprice()
    {
        return $this->hasOne('App\ItemPrice')->latest();
    }
}
