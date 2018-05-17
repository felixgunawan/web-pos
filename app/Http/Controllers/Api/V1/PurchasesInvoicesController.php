<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\PurchasesInvoice;
use App\Purchase;
use Carbon\Carbon;

class PurchasesInvoicesController extends Controller
{
    public function soonDebt()
    {
        return PurchasesInvoice::select(DB::raw('ADDDATE(purchases_invoices.created_at, suppliers.deadline) AS payment_date'),'suppliers.name','purchases_invoices.paid','purchases_invoices.total')
                    ->join('suppliers', 'purchases_invoices.supplier_id', '=', 'suppliers.id')
                    ->where('purchases_invoices.deleted','=',false)
                    ->whereRaw('purchases_invoices.paid < purchases_invoices.total')
                    ->groupBy('suppliers.name','purchases_invoices.created_at','suppliers.deadline','purchases_invoices.paid','purchases_invoices.total')
                    ->orderBy('payment_date')
                    ->take(10)
                    ->get();
    }

    public function totalDebt()
    {
        return PurchasesInvoice::where('deleted','=',false)
                    ->sum(DB::raw('(total-paid)'));
    }
    
    public function chart()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;

        return PurchasesInvoice::select(DB::raw('SUM(total) As total_purchased, DATE(created_at) as day'))
                    ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                       $query->where('purchases.created_at', '>=', $keywordStartDate)
                           ->where('purchases.created_at', '<=', $keywordEndDate);
                       })
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();
    }

    public function outcome()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        return PurchasesInvoice::where('purchases_invoices.deleted','=',false)
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('purchases_invoices.created_at', '>=', $keywordStartDate)
                            ->where('purchases_invoices.created_at', '<=', $keywordEndDate);
                    })
                ->sum('total');
    }

    public function newId()
    {
        return PurchasesInvoice::max('id')+1;
    }

    public function currentUser()
    {
        return auth()->user();
    }
    
    public function invoice()
    {
        return PurchasesInvoice::max('purchase_invoice_no')+1;
    }

    public function recount()
    {
        Purchase::where('deleted','=',false)
            ->join('item_prices', 'purchases.itemprice_id', '=', 'item_prices.id')
            ->getQuery()
            ->update(['total_purchase' => DB::raw('qty * item_prices.sell_price'),
                    'purchases.updated_at' => Carbon::now(),
            ]);

        DB::select('UPDATE purchases_invoices a
            INNER JOIN (
            SELECT purchasesinvoice_id, SUM(total_purchase) as totalpurchase
            FROM purchases
            GROUP BY purchasesinvoice_id
            ) b ON a.id = b.purchasesinvoice_id
            SET a.total = b.totalpurchase');
        return 0;
    }

    public function index()
    {
        $keywordPurchaseInvoiceNo = isset($_GET['searchVarPurchaseInvoiceNo']) ? $_GET['searchVarPurchaseInvoiceNo'] : NULL;
        $keywordUserName = isset($_GET['searchVarUserName']) ? $_GET['searchVarUserName'] : NULL;
        $keywordSupplierName = isset($_GET['searchVarSupplierName']) ? $_GET['searchVarSupplierName'] : NULL;
        $keywordPurchaseInvoiceNo = isset($_GET['searchVarPurchaseInvoiceNo']) ? $_GET['searchVarPurchaseInvoiceNo'] : NULL;
        $keywordTotal = isset($_GET['searchVarTotal']) ? $_GET['searchVarTotal'] : NULL;
        $keywordPaid = isset($_GET['searchVarPaid']) ? $_GET['searchVarPaid'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;

        return PurchasesInvoice::select('purchases_invoices.*')
                ->where('purchases_invoices.deleted','=',false)->with(['user','supplier'])
                ->join('users', 'purchases_invoices.user_id', '=', 'users.id')
                ->join('suppliers', 'purchases_invoices.supplier_id', '=', 'suppliers.id')        
                ->when($keywordUserName, function ($query) use ($keywordUserName){
                    $query->whereHas('user' , function ($query) use ($keywordUserName){
                        $query->where('name', 'like', '%'.$keywordUserName.'%');
                        });
                    })
                ->when($keywordSupplierName, function ($query) use ($keywordSupplierName){
                    $query->whereHas('supplier' , function ($query) use ($keywordSupplierName){$query->where('name', 'like', '%'.$keywordSupplierName.'%');
                        });
                    })
                ->when($keywordPurchaseInvoiceNo, function ($query) use ($keywordPurchaseInvoiceNo){
                    $query->where('purchase_invoice_no','like','%'.$keywordPurchaseInvoiceNo.'%');
                    })
                ->when($keywordTotal, function ($query) use ($keywordTotal){
                    $query->where('total','like','%'.$keywordTotal.'%');
                    })
                ->when($keywordPaid, function ($query) use ($keywordPaid){
                    $query->where('paid','like','%'.$keywordPaid.'%');
                    })
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('purchases_invoices.created_at', '>=', $keywordStartDate)
                            ->where('purchases_invoices.created_at', '<=', $keywordEndDate);
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

     public function show($id)
    {
        return PurchasesInvoice::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'paid' => 'required|numeric|min:1'
        ]);

        $purchase_invoice = PurchasesInvoice::findOrFail($id);
        $purchase_invoice->paid = $request->input('paid');
        $purchase_invoice->save();

        return $purchase_invoice;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'purchase_invoice_no' => 'required|numeric',
            'total' => 'required|numeric',
            'paid' => 'required|numeric',
            'change' => 'required|numeric',
        ]);
       
        $purchase_invoice = new PurchasesInvoice;
        $purchase_invoice->user_id = $request->input('user_id');
        $purchase_invoice->supplier_id = $request->input('supplier_id');
        $purchase_invoice->purchase_invoice_no = $request->input('purchase_invoice_no');
        $purchase_invoice->total = $request->input('total');
        $purchase_invoice->paid = $request->input('paid');
        $purchase_invoice->change = $request->input('change');
        $purchase_invoice->save();
        return $purchase_invoice;
    }

    public function destroy($id)
    {
        $purchase_invoice = PurchasesInvoice::findOrFail($id);
        $purchase_invoice->deleted = true;
        $purchase_invoice->save();          
        return '';
    }
}
