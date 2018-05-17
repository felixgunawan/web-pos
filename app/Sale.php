<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
    protected $fillable = ['location_id','salesinvoice_id','item_id','itemprice_id','qty','total_sale','deleted'];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function itemprice()
    {
        return $this->belongsTo('App\ItemPrice');
    }
    public function salesinvoice()
    {
        return $this->belongsTo('App\SalesInvoice');
    }
}
