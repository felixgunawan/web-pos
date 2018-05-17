<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Purchase;
use App\Item;
use App\ItemPrice;
use App\Stock;
use Carbon\Carbon;

class PurchasesController extends Controller
{
    public function profit()
    {
        return Purchase::where('purchases.deleted','=',false)->join('items', 'purchases.item_id', '=', 'items.id')
                ->sum(DB::raw('(items.sell_price - items.buy_price) * purchases.qty'));
    }

    public function recount()
    {
        Purchase::where('deleted','=',false)
            ->join('item_prices', 'purchases.itemprice_id', '=', 'item_prices.id')
            ->getQuery()
            ->update(['total_purchase' => DB::raw('qty * item_prices.sell_price'),
                    'purchases.updated_at' => Carbon::now(),
            ]);
        return 0;
    }

    public function index()
    {
        $keywordPurchaseInvoiceNo = isset($_GET['searchVarPurchaseInvoiceNo']) ? $_GET['searchVarPurchaseInvoiceNo'] : NULL;
        $keywordItemName = isset($_GET['searchVarItemName']) ? $_GET['searchVarItemName'] : NULL;
        $keywordPrice = isset($_GET['searchVarPrice']) ? $_GET['searchVarPrice'] : NULL;
        $keywordQty = isset($_GET['searchVarQty']) ? $_GET['searchVarQty'] : NULL;
        $keywordTotal = isset($_GET['searchVarTotal']) ? $_GET['searchVarTotal'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;

        return Purchase::select('purchases.*')
                ->where('purchases.deleted','=',false)->with(['item','purchasesinvoice','itemprice'])
                ->join('items', 'purchases.item_id', '=', 'items.id')
                ->join('purchases_invoices', 'purchases.purchasesinvoice_id', '=', 'purchases_invoices.id')
                ->join('item_prices', 'purchases.itemprice_id', '=', 'item_prices.id')            
                ->when($keywordPurchaseInvoiceNo, function ($query) use ($keywordPurchaseInvoiceNo){
                    $query->whereHas('purchasesinvoice' , function ($query) use ($keywordPurchaseInvoiceNo){
                        $query->where('purchase_invoice_no', 'like', '%'.$keywordPurchaseInvoiceNo.'%');
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
                    $query->where('total_purchase','like','%'.$keywordTotal.'%');
                    })
                ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                    $query->where('purchases.created_at', '>=', $keywordStartDate)
                            ->where('purchases.created_at', '<=', $keywordEndDate);
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

    public function show($id)
    {
        return Purchase::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'purchasesinvoice_id' => 'required|numeric|min:1',
            'item_id' => 'required|numeric|min:1',
            'itemprice_id' => 'required|numeric|min:1',
            'qty' => 'required|numeric|min:1',
            'total_purchase' => 'required|numeric|min:1',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->purchasesinvoice_id = $request->input('purchasesinvoice_id');
        $purchase->item_id = $request->input('item_id');
        $purchase->itemprice_id = $request->input('itemprice_id');
        $purchase->qty = $request->input('qty');
        $purchase->total_purchase = $request->input('total_purchase');
        $purchase->save();

        return $purchase;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'purchasesinvoice_id' => 'required|numeric|min:1',
            'item_id' => 'required|numeric|min:1',
            'itemprice_id' => 'required|numeric|min:1',
            'qty' => 'required|numeric|min:1',
            'total_purchase' => 'required|numeric|min:1',
        ]);
        $location_id = auth()->user()->location_id;
        $purchase = new Purchase;
        $purchase->location_id = $location_id;
        $purchase->purchasesinvoice_id = $request->input('purchasesinvoice_id');
        $purchase->item_id = $request->input('item_id');
        $purchase->itemprice_id = $request->input('itemprice_id');
        $purchase->qty = $request->input('qty');
        $purchase->total_purchase = $request->input('total_purchase');
        $purchase->save();

        $item = Item::findOrFail($request->input('item_id'));
        $item->increment('in_stock',$request->input('qty'));
        $item->increment('now_stock',$request->input('qty'));
        
        $stock = Stock::where('item_id','=',$request->input('item_id'))->where('location_id','=',$location_id)->increment('in_stock',$request->input('qty'));
        $stock = Stock::where('item_id','=',$request->input('item_id'))->where('location_id','=',$location_id)->increment('now_stock',$request->input('qty'));
        return $purchase;
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->deleted = true;
        $purchase->save();          
        return '';
    }
}
