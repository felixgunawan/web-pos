<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';
    protected $fillable = ['location_id','purchasesinvoice_id','item_id','itemprice_id','qty','total_purchase','deleted'];

    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function itemprice()
    {
        return $this->belongsTo('App\ItemPrice');
    }
    public function purchasesinvoice()
    {
        return $this->belongsTo('App\PurchasesInvoice');
    }
}
