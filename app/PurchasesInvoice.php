<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchasesInvoice extends Model
{
    protected $table = 'purchases_invoices';
    protected $fillable = ['user_id','supplier_id','purchase_invoice_no','total','paid','change','deleted'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
}
