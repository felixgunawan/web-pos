<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\SalesInvoice;
use App\Sale;
use Carbon\Carbon;

class SalesInvoicesController extends Controller
{
    public function chart()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        return SalesInvoice::select(DB::raw('SUM(total) As total_sold, DATE(created_at) as day'))
                     ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                        $query->where('sales.created_at', '>=', $keywordStartDate)
                            ->where('sales.created_at', '<=', $keywordEndDate);
                        })
                     ->groupBy('day')
                     ->orderBy('day')
                     ->get();
    }
    public function income()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        return SalesInvoice::where('sales_invoices.deleted','=',false)
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('sales_invoices.created_at', '>=', $keywordStartDate)
                            ->where('sales_invoices.created_at', '<=', $keywordEndDate);
                    })
                ->sum('total');
    }

    public function newId()
    {
        return SalesInvoice::max('id')+1;
    }

    public function currentUser()
    {
        return auth()->user();
    }
    
    public function invoice()
    {
        return SalesInvoice::max('sale_invoice_no')+1;
    }

    public function recount()
    {
        Sale::where('deleted','=',false)
            ->join('item_prices', 'sales.itemprice_id', '=', 'item_prices.id')
            ->getQuery()
            ->update(['total_sale' => DB::raw('qty * item_prices.sell_price'),
                    'sales.updated_at' => Carbon::now(),
            ]);

        DB::select('UPDATE sales_invoices a
            INNER JOIN (
            SELECT salesinvoice_id, SUM(total_sale) as totalsale
            FROM sales
            GROUP BY salesinvoice_id
            ) b ON a.id = b.salesinvoice_id
            SET a.total = b.totalsale');
        return 0;
    }

    public function index()
    {
        $keywordSaleInvoiceNo = isset($_GET['searchVarSaleInvoiceNo']) ? $_GET['searchVarSaleInvoiceNo'] : NULL;
        $keywordUserName = isset($_GET['searchVarUserName']) ? $_GET['searchVarUserName'] : NULL;
        $keywordSaleInvoiceNo = isset($_GET['searchVarSaleInvoiceNo']) ? $_GET['searchVarSaleInvoiceNo'] : NULL;
        $keywordTotal = isset($_GET['searchVarTotal']) ? $_GET['searchVarTotal'] : NULL;
        $keywordPaid = isset($_GET['searchVarPaid']) ? $_GET['searchVarPaid'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        
        return SalesInvoice::select('sales_invoices.*')
                ->where('sales_invoices.deleted','=',false)->with(['user'])
                ->join('users', 'sales_invoices.user_id', '=', 'users.id')
                ->when($keywordUserName, function ($query) use ($keywordSaleInvoiceNo){
                    $query->whereHas('user' , function ($query) use ($keywordSaleInvoiceNo){
                        $query->where('name', 'like', '%'.$keywordUserName.'%');
                        });
                    })
                ->when($keywordSaleInvoiceNo, function ($query) use ($keywordSaleInvoiceNo){
                    $query->where('sale_invoice_no','like','%'.$keywordSaleInvoiceNo.'%');
                    })
                ->when($keywordTotal, function ($query) use ($keywordTotal){
                    $query->where('total','like','%'.$keywordTotal.'%');
                    })
                ->when($keywordPaid, function ($query) use ($keywordPaid){
                    $query->where('paid','like','%'.$keywordPaid.'%');
                    })
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('sales_invoices.created_at', '>=', $keywordStartDate)
                            ->where('sales_invoices.created_at', '<=', $keywordEndDate);
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

     public function show($id)
    {
        return SalesInvoice::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'user_id' => 'required|numeric',
            'sale_invoice_no' => 'required|numeric',
            'total' => 'required|numeric|min:1',
            'paid' => 'required|numeric|greater_than_field:total|min:1',
            'change' => 'required|numeric',
        ]);

        $sale_invoice = SalesInvoice::findOrFail($id);
        $sale_invoice->user_id = $request->input('user_id');
        $sale_invoice->sale_invoice_no = $request->input('sale_invoice_no');
        $sale_invoice->total = $request->input('total');
        $sale_invoice->paid = $request->input('paid');
        $sale_invoice->change = $request->input('change');
        $sale_invoice->save();

        return $sale_invoice;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required|numeric',
            'sale_invoice_no' => 'required|numeric',
            'total' => 'required|numeric|min:1',
            'paid' => 'required|numeric|greater_than_field:total|min:1',
            'change' => 'required|numeric',
        ]);
       
        $sale_invoice = new SalesInvoice;
        $sale_invoice->user_id = $request->input('user_id');
        $sale_invoice->sale_invoice_no = $request->input('sale_invoice_no');
        $sale_invoice->total = $request->input('total');
        $sale_invoice->paid = $request->input('paid');
        $sale_invoice->change = $request->input('change');
        $sale_invoice->save();
        return $sale_invoice;
    }

    public function destroy($id)
    {
        $sale_invoice = SalesInvoice::findOrFail($id);
        $sale_invoice->deleted = true;
        $sale_invoice->save();          
        return '';
    }
}
