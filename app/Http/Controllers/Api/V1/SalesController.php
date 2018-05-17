<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Sale;
use App\Item;
use App\ItemPrice;
use App\Stock;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function chart()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        return Sale::select(DB::raw('SUM(total_sale) As total_sold, SUM((item_prices.sell_price - item_prices.buy_price) * sales.qty) as profit, DATE(sales.created_at) as day'))
                    ->join('item_prices', 'sales.itemprice_id', '=', 'item_prices.id')
                     ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                        $query->where('sales.created_at', '>=', $keywordStartDate)
                            ->where('sales.created_at', '<=', $keywordEndDate);
                        })
                     ->groupBy('day')
                     ->orderBy('day')
                     ->get();
    }

    public function profit()
    {
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        return Sale::where('sales.deleted','=',false)->join('item_prices', 'sales.itemprice_id', '=', 'item_prices.id')
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('sales.created_at', '>=', $keywordStartDate)
                            ->where('sales.created_at', '<=', $keywordEndDate);
                    })
                ->sum(DB::raw('(item_prices.sell_price - item_prices.buy_price) * sales.qty'));
    }

    public function recount()
    {
        Sale::where('deleted','=',false)
            ->join('item_prices', 'sales.itemprice_id', '=', 'item_prices.id')
            ->getQuery()
            ->update(['total_sale' => DB::raw('qty * item_prices.sell_price'),
                    'sales.updated_at' => Carbon::now(),
            ]);
        return 0;
    }

    public function index()
    {
        $keywordSaleInvoiceNo = isset($_GET['searchVarSaleInvoiceNo']) ? $_GET['searchVarSaleInvoiceNo'] : NULL;
        $keywordItemName = isset($_GET['searchVarItemName']) ? $_GET['searchVarItemName'] : NULL;
        $keywordPrice = isset($_GET['searchVarPrice']) ? $_GET['searchVarPrice'] : NULL;
        $keywordQty = isset($_GET['searchVarQty']) ? $_GET['searchVarQty'] : NULL;
        $keywordTotal = isset($_GET['searchVarTotal']) ? $_GET['searchVarTotal'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;
        
        return Sale::select('sales.*')
                ->where('sales.deleted','=',false)->with(['item','salesinvoice','itemprice'])
                ->join('items', 'sales.item_id', '=', 'items.id')
                ->join('sales_invoices', 'sales.salesinvoice_id', '=', 'sales_invoices.id')
                ->join('item_prices', 'sales.itemprice_id', '=', 'item_prices.id')            
                ->when($keywordSaleInvoiceNo, function ($query) use ($keywordSaleInvoiceNo){
                    $query->whereHas('salesinvoice' , function ($query) use ($keywordSaleInvoiceNo){
                        $query->where('sale_invoice_no', 'like', '%'.$keywordSaleInvoiceNo.'%');
                        });
                    })
                ->when($keywordItemName, function ($query) use ($keywordItemName){
                    $query->whereHas('item' , function ($query) use ($keywordItemName){
                        $query->where('item_name', 'like', '%'.$keywordItemName.'%');
                        });
                    })
                ->when($keywordPrice, function ($query) use ($keywordPrice){
                    $query->whereHas('itemprice' , function ($query) use ($keywordPrice){
                        $query->where('sell_price', 'like', '%'.$keywordPrice.'%');
                        });
                    })
                ->when($keywordQty, function ($query) use ($keywordQty){
                    $query->where('qty','like','%'.$keywordQty.'%');
                    })
                ->when($keywordTotal, function ($query) use ($keywordTotal){
                    $query->where('total_sale','like','%'.$keywordTotal.'%');
                    })
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('sales.created_at', '>=', $keywordStartDate)
                            ->where('sales.created_at', '<=', $keywordEndDate);
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

    public function show($id)
    {
        return Sale::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'salesinvoice_id' => 'required|numeric|min:1',
            'item_id' => 'required|numeric|min:1',
            'itemprice_id' => 'required|numeric|min:1',
            'qty' => 'required|numeric|min:1',
            'total_sale' => 'required|numeric|min:1',
        ]);

        $sale = Sale::findOrFail($id);
        $sale->salesinvoice_id = $request->input('salesinvoice_id');
        $sale->item_id = $request->input('item_id');
        $sale->itemprice_id = $request->input('itemprice_id');
        $sale->qty = $request->input('qty');
        $sale->total_sale = $request->input('total_sale');
        $sale->save();

        return $sale;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'salesinvoice_id' => 'required|numeric|min:1',
            'item_id' => 'required|numeric|min:1',
            'itemprice_id' => 'required|numeric|min:1',
            'qty' => 'required|numeric|min:1',
            'total_sale' => 'required|numeric|min:1',
        ]);
        $location_id = auth()->user()->location_id;
        $sale = new Sale;
        $sale->location_id = $location_id;
        $sale->salesinvoice_id = $request->input('salesinvoice_id');
        $sale->item_id = $request->input('item_id');
        $sale->itemprice_id = $request->input('itemprice_id');
        $sale->qty = $request->input('qty');
        $sale->total_sale = $request->input('total_sale');
        $sale->save();

        $item = Item::findOrFail($request->input('item_id'));
        $item->increment('out_stock',$request->input('qty'));
        $item->decrement('now_stock',$request->input('qty'));
        
        $stock = Stock::where('item_id','=',$request->input('item_id'))->where('location_id','=',$location_id)->increment('out_stock',$request->input('qty'));
        $stock = Stock::where('item_id','=',$request->input('item_id'))->where('location_id','=',$location_id)->decrement('now_stock',$request->input('qty'));
        
        return $sale;
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->deleted = true;
        $sale->save();          
        return '';
    }
}
