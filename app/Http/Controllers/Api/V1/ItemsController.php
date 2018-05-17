<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemPrice;
use App\Supplier;
use App\Department;
use App\Sale;
use App\Stock;
use App\Location;
use Carbon\Carbon;

class ItemsController extends Controller
{
    public function index()
    {
        $keywordItemCode = isset($_GET['searchVarItemCode']) ? $_GET['searchVarItemCode'] : NULL;
        $keywordSupplierCode = isset($_GET['searchVarSupplierCode']) ? $_GET['searchVarSupplierCode'] : NULL;
        $keywordDepartmentCode = isset($_GET['searchVarDepartmentCode']) ? $_GET['searchVarDepartmentCode'] : NULL;
        $keywordName = isset($_GET['searchVarName']) ? $_GET['searchVarName'] : NULL;
        $keywordUnit = isset($_GET['searchVarUnit']) ? $_GET['searchVarUnit'] : NULL;
        $keywordBuyPrice = isset($_GET['searchVarBuyPrice']) ? $_GET['searchVarBuyPrice'] : NULL;
        $keywordSellPrice = isset($_GET['searchVarSellPrice']) ? $_GET['searchVarSellPrice'] : NULL;
        $keywordFirstStock = isset($_GET['searchVarFirstStock']) ? $_GET['searchVarFirstStock'] : NULL;
        $keywordInStock = isset($_GET['searchVarInStock']) ? $_GET['searchVarInStock'] : NULL;
        $keywordOutStock = isset($_GET['searchVarOutStock']) ? $_GET['searchVarOutStock'] : NULL;
        $keywordNowStock = isset($_GET['searchVarNowStock']) ? $_GET['searchVarNowStock'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        
        return Item::where('items.deleted','=',false)->with(['supplier:id,code','department:id,code','itemprice'])
                ->when($keywordItemCode, function ($query) use ($keywordItemCode){
                    $query->where('items.item_code','like','%'.$keywordItemCode.'%');
                    })
                ->when($keywordSupplierCode, function ($query) use ($keywordSupplierCode){
                    $query->whereHas('supplier' , function ($query) use ($keywordSupplierCode){
                        $query->where('code', 'like', '%'.$keywordSupplierCode.'%');
                        });
                    })
                ->when($keywordDepartmentCode, function ($query) use ($keywordDepartmentCode){
                    $query->whereHas('department' , function ($query) use ($keywordDepartmentCode){
                        $query->where('code', 'like', '%'.$keywordDepartmentCode.'%');
                        });
                    })
                ->when($keywordName, function ($query) use ($keywordName){
                    $query->where('item_name','like','%'.$keywordName.'%');
                    })
                ->when($keywordUnit, function ($query) use ($keywordUnit){
                    $query->where('unit','like','%'.$keywordUnit.'%');
                    })
                ->when($keywordBuyPrice, function ($query) use ($keywordBuyPrice){
                    $query->whereHas('itemprice' , function ($query) use ($keywordBuyPrice){
                        $query->where('buy_price', 'like', '%'.$keywordBuyPrice.'%');
                        });
                    })
                ->when($keywordSellPrice, function ($query) use ($keywordSellPrice){
                    $query->whereHas('itemprice' , function ($query) use ($keywordSellPrice){
                        $query->where('sell_price', 'like', '%'.$keywordSellPrice.'%');
                        });
                    })
                ->when($keywordFirstStock, function ($query) use ($keywordFirstStock){
                    $query->where('first_stock','like','%'.$keywordFirstStock.'%');
                    })
                ->when($keywordInStock, function ($query) use ($keywordInStock){
                    $query->where('in_stock','like','%'.$keywordInStock.'%');
                    })
                ->when($keywordOutStock, function ($query) use ($keywordOutStock){
                    $query->where('out_stock','like','%'.$keywordOutStock.'%');
                    })
                ->when($keywordNowStock, function ($query) use ($keywordNowStock){
                    $query->where('now_stock','like','%'.$keywordNowStock.'%');
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                            ->join('departments', 'items.department_id', '=', 'departments.id')
                            ->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

    public function show($id)
    {
        return Item::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'supplier_id' => 'required|exists:suppliers,id',
            'department_id' => 'required|exists:departments,id',
            'item_code' => 'required',
            'item_name' => 'required',
            'unit' => 'required',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric', 
        ]);

        $item = Item::findOrFail($id);
        $item->supplier_id = $request->input('supplier_id');
        $item->department_id = $request->input('department_id');
        $item->item_code = $request->input('item_code');
        $item->item_name = $request->input('item_name');
        $item->unit = $request->input('unit');
        $item->save();

        $check = Item::find($id)->itemprice;
        if(($check->sell_price != $request->input('sell_price'))||($check->buy_price != $request->input('buy_price'))){
            $itemprice = new ItemPrice;
            $itemprice->item_id = $id;
            $itemprice->buy_price = $request->input('buy_price');
            $itemprice->sell_price = $request->input('sell_price');
            $itemprice->save();
        }
        return $item;
    }

    public function store(Request $request)
    {   
             
        $this->validate($request,[
            'location_id' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'department_id' => 'required|exists:departments,id',
            'item_code' => 'required',
            'item_name' => 'required',
            'unit' => 'required',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric', 
        ]);
        
        $item = new Item;
        $item->supplier_id = $request->input('supplier_id');
        $item->department_id = $request->input('department_id');
        $item->item_code = $request->input('item_code');
        $item->item_name = $request->input('item_name');
        $item->unit = $request->input('unit');
        $item->first_stock = $request->input('first_stock');
        $item->now_stock = $request->input('first_stock');
        $item->save();

        $id = Item::max('id');
        $itemprice = new ItemPrice;
        $itemprice->item_id = $id;
        $itemprice->buy_price = $request->input('buy_price');
        $itemprice->sell_price = $request->input('sell_price');
        $itemprice->save();
        
        $stock = new Stock;
        $stock->location_id = $request->input('location_id');
        $stock->item_id = $id;
        $stock->first_stock = $request->input('first_stock');
        $stock->now_stock = $request->input('first_stock');
        $stock->save();

        $max = Location::max('id');
        for($i = 1; $i <= $max; $i++){
            if($i != $request->input('location_id')){
                $stock = new Stock;
                $stock->location_id = $i;
                $stock->item_id = $id;
                $stock->first_stock = 0;
                $stock->now_stock = 0;
                $stock->save();
            }
        }
        return $item;
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->deleted = true;
        $item->save();          
        return '';
    }

    public function stock($id)
    {
        return Stock::where('item_id','=',$id)->get();
    }

    public function supplier($id)
    {
        return Item::find($id)->supplier;
    }

    public function department($id)
    {
        return Item::find($id)->department;
    }

    public function search($searchVar)
    {
        if(strlen($searchVar)>1){
            return Item::where('deleted','=',false)->with('itemprice')->where('item_name','like','%'.$searchVar.'%')->get();
        }
    }
    public function supplierBasedSearch($supplier)
    {
        return Item::where('deleted','=',false)->with('itemprice')->where('supplier_id','=',$supplier)->get();
    }

    public function supplierBasedItemSearch($supplier,$item_name)
    {
        return Item::where('deleted','=',false)->with('itemprice')->where('supplier_id','=',$supplier)->where('item_name','=',$item_name)->get();
    }

    public function recount()
    {
        DB::select('UPDATE items i
            INNER JOIN (
            SELECT item_id, SUM(qty) as totalqty
            FROM sales
            GROUP BY item_id
            ) s ON i.id = s.item_id
            SET i.out_stock = s.totalqty, i.now_stock = i.first_stock - s.totalqty');
        DB::select('UPDATE items i
            INNER JOIN (
            SELECT item_id, SUM(qty) as totalqty
            FROM purchases
            GROUP BY item_id
            ) s ON i.id = s.item_id
            SET i.in_stock = s.totalqty, i.now_stock = i.first_stock - i.out_stock + s.totalqty');
        DB::select('UPDATE stocks i
            INNER JOIN (
            SELECT item_id, location_id, SUM(qty) as totalqty
            FROM sales
            GROUP BY item_id, location_id
            ) s ON i.item_id = s.item_id
            SET i.out_stock = s.totalqty, i.now_stock = i.first_stock - s.totalqty
            WHERE s.location_id = i.location_id');
        DB::select('UPDATE stocks i
            INNER JOIN (
            SELECT item_id, location_id, SUM(qty) as totalqty
            FROM purchases
            GROUP BY item_id, location_id
            ) s ON i.item_id = s.item_id
            SET i.in_stock = s.totalqty, i.now_stock = i.first_stock - i.out_stock + s.totalqty
            WHERE s.location_id = i.location_id');
        return 0;
    }

    public function report()
    {
        $keywordDepartmentName = isset($_GET['searchVarDepartmentName']) ? $_GET['searchVarDepartmentName'] : NULL;
        $keywordDepartmentCode = isset($_GET['searchVarDepartmentCode']) ? $_GET['searchVarDepartmentCode'] : NULL;
        $keywordSupplierName = isset($_GET['searchVarSupplierName']) ? $_GET['searchVarSupplierName'] : NULL;
        $keywordSupplierCode = isset($_GET['searchVarSupplierCode']) ? $_GET['searchVarSupplierCode'] : NULL;
        $keywordItemName = isset($_GET['searchVarItemName']) ? $_GET['searchVarItemName'] : NULL;
        $keywordItemCode = isset($_GET['searchVarItemCode']) ? $_GET['searchVarItemCode'] : NULL;
        /*
        $keywordQtySold = isset($_GET['searchVarQtySold']) ? $_GET['searchVarQtySold'] : NULL;
        $keywordTotalSold = isset($_GET['searchVarTotalSold']) ? $_GET['searchVarTotalSold'] : NULL;
        $keywordProfit = isset($_GET['searchVarProfit']) ? $_GET['searchVarProfit'] : NULL;
        */
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;

        return Item::select('items.item_name', 'items.item_code','suppliers.code As supplier_code','suppliers.name As supplier_name','departments.code As department_code','departments.name As department_name', DB::raw('SUM(sales.qty) As qty_sold'), DB::raw('SUM(sales.total_sale) As total_sold'), DB::raw('SUM(sales.qty) * (item_prices.sell_price - item_prices.buy_price) As profit'))
                     ->join('departments', 'departments.id', '=', 'items.department_id')
                     ->join('suppliers', 'suppliers.id', '=', 'items.supplier_id')   
                     ->join('sales', 'sales.item_id', '=', 'items.id')
                     ->join('item_prices', 'sales.itemprice_id', '=' ,'item_prices.id')
                     ->when($keywordDepartmentName, function ($query) use ($keywordDepartmentName){
                        $query->where('departments.name','like','%'.$keywordDepartmentName.'%');
                        })
                     ->when($keywordDepartmentCode, function ($query) use ($keywordDepartmentCode){
                        $query->where('departments.code','like','%'.$keywordDepartmentCode.'%');
                        })
                     ->when($keywordSupplierName, function ($query) use ($keywordSupplierName){
                        $query->where('suppliers.name','like','%'.$keywordSupplierName.'%');
                        })
                     ->when($keywordSupplierCode, function ($query) use ($keywordSupplierCode){
                        $query->where('suppliers.code','like','%'.$keywordSupplierCode.'%');
                        })
                     ->when($keywordItemName, function ($query) use ($keywordItemName){
                        $query->where('item_name','like','%'.$keywordItemName.'%');
                        })
                     ->when($keywordItemCode, function ($query) use ($keywordItemCode){
                        $query->where('item_code','like','%'.$keywordItemCode.'%');
                        })
                     /*
                     ->when($keywordQtySold, function ($query) use ($keywordQtySold){
                        $query->where('qty_sold','like','%'.$keywordQtySold.'%');
                        })
                     ->when($keywordTotalSold, function ($query) use ($keywordTotalSold){
                        $query->where('total_sold','like','%'.$keywordTotalSold.'%');
                        })
                     ->when($keywordProfit, function ($query) use ($keywordProfit){
                        $query->where('profit','like','%'.$keywordProfit.'%');
                        })
                    */
                     ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                        $query->where('sales.created_at', '>=', $keywordStartDate)
                            ->where('sales.created_at', '<=', $keywordEndDate);
                        })
                     ->groupBy('departments.name','departments.code','suppliers.name','suppliers.code','items.item_name','items.item_code','item_prices.sell_price','item_prices.buy_price')
                     ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                        $query->orderBy($orderWord,$isAsc);
                        })
                     ->paginate(20);
    }
    
    public function reportpurchase()
    {
        $keywordDepartmentName = isset($_GET['searchVarDepartmentName']) ? $_GET['searchVarDepartmentName'] : NULL;
        $keywordDepartmentCode = isset($_GET['searchVarDepartmentCode']) ? $_GET['searchVarDepartmentCode'] : NULL;
        $keywordSupplierName = isset($_GET['searchVarSupplierName']) ? $_GET['searchVarSupplierName'] : NULL;
        $keywordSupplierCode = isset($_GET['searchVarSupplierCode']) ? $_GET['searchVarSupplierCode'] : NULL;
        $keywordItemName = isset($_GET['searchVarItemName']) ? $_GET['searchVarItemName'] : NULL;
        $keywordItemCode = isset($_GET['searchVarItemCode']) ? $_GET['searchVarItemCode'] : NULL;
        /*
        $keywordQtySold = isset($_GET['searchVarQtySold']) ? $_GET['searchVarQtySold'] : NULL;
        $keywordTotalSold = isset($_GET['searchVarTotalSold']) ? $_GET['searchVarTotalSold'] : NULL;
        $keywordProfit = isset($_GET['searchVarProfit']) ? $_GET['searchVarProfit'] : NULL;
        */
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        $keywordStartDate = isset($_GET['searchVarStartDate']) ? $_GET['searchVarStartDate'] : NULL;
        $keywordEndDate = isset($_GET['searchVarEndDate']) ? $_GET['searchVarEndDate'] : NULL;

        return Item::select('items.item_name', 'items.item_code','suppliers.code As supplier_code','suppliers.name As supplier_name','departments.code As department_code','departments.name As department_name', DB::raw('SUM(purchases.qty) As qty_purchased'), DB::raw('SUM(purchases.total_purchase) As total_purchased'))
                     ->join('departments', 'departments.id', '=', 'items.department_id')
                     ->join('suppliers', 'suppliers.id', '=', 'items.supplier_id')   
                     ->join('purchases', 'purchases.item_id', '=', 'items.id')
                     ->join('item_prices', 'purchases.itemprice_id', '=' ,'item_prices.id')
                     ->when($keywordDepartmentName, function ($query) use ($keywordDepartmentName){
                        $query->where('departments.name','like','%'.$keywordDepartmentName.'%');
                        })
                     ->when($keywordDepartmentCode, function ($query) use ($keywordDepartmentCode){
                        $query->where('departments.code','like','%'.$keywordDepartmentCode.'%');
                        })
                     ->when($keywordSupplierName, function ($query) use ($keywordSupplierName){
                        $query->where('suppliers.name','like','%'.$keywordSupplierName.'%');
                        })
                     ->when($keywordSupplierCode, function ($query) use ($keywordSupplierCode){
                        $query->where('suppliers.code','like','%'.$keywordSupplierCode.'%');
                        })
                     ->when($keywordItemName, function ($query) use ($keywordItemName){
                        $query->where('item_name','like','%'.$keywordItemName.'%');
                        })
                     ->when($keywordItemCode, function ($query) use ($keywordItemCode){
                        $query->where('item_code','like','%'.$keywordItemCode.'%');
                        })
                     /*
                     ->when($keywordQtySold, function ($query) use ($keywordQtySold){
                        $query->where('qty_sold','like','%'.$keywordQtySold.'%');
                        })
                     ->when($keywordTotalSold, function ($query) use ($keywordTotalSold){
                        $query->where('total_sold','like','%'.$keywordTotalSold.'%');
                        })
                     ->when($keywordProfit, function ($query) use ($keywordProfit){
                        $query->where('profit','like','%'.$keywordProfit.'%');
                        })
                    */
                     ->when($keywordStartDate, function ($query) use ($keywordStartDate,$keywordEndDate){
                        $query->where('purchases.created_at', '>=', $keywordStartDate)
                            ->where('purchases.created_at', '<=', $keywordEndDate);
                        })
                     ->groupBy('departments.name','departments.code','suppliers.name','suppliers.code','items.item_name','items.item_code','item_prices.sell_price','item_prices.buy_price')
                     ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                        $query->orderBy($orderWord,$isAsc);
                        })
                     ->paginate(20);
    }
}
