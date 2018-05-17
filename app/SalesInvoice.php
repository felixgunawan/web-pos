<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $table = 'sales_invoices';
    protected $fillable = ['user_id','sale_invoice_no','total','paid','change','deleted'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
