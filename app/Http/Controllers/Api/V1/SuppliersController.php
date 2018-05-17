<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;

class SuppliersController extends Controller
{
    public function index()
    {
		$keywordCode = isset($_GET['searchVarCode']) ? $_GET['searchVarCode'] : NULL;
		$keywordName = isset($_GET['searchVarName']) ? $_GET['searchVarName'] : NULL;
		$keywordAddress = isset($_GET['searchVarAddress']) ? $_GET['searchVarAddress'] : NULL;
		$keywordCity = isset($_GET['searchVarCity']) ? $_GET['searchVarCity'] : NULL;
		$keywordPhone = isset($_GET['searchVarPhone']) ? $_GET['searchVarPhone'] : NULL;
		$keywordDeadline = isset($_GET['searchVarDeadline']) ? $_GET['searchVarDeadline'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
		$isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        return Supplier::where('deleted','=',false)
				->when($keywordCode, function ($query) use ($keywordCode){
					$query->where('code','like','%'.$keywordCode.'%');
					})
				->when($keywordName, function ($query) use ($keywordName){
					$query->where('name','like','%'.$keywordName.'%');
					})
				->when($keywordAddress, function ($query) use ($keywordAddress){
					$query->where('address','like','%'.$keywordAddress.'%');
					})
				->when($keywordCity, function ($query) use ($keywordCity){
					$query->where('city','like','%'.$keywordCity.'%');
					})
				->when($keywordPhone, function ($query) use ($keywordPhone){
					$query->where('phone','like','%'.$keywordPhone.'%');
					})
				->when($keywordDeadline, function ($query) use ($keywordDeadline){
					$query->where('deadline','like','%'.$keywordDeadline.'%');
					})
				->when($orderWord, function ($query) use ($orderWord, $isAsc){
					$query->orderBy($orderWord,$isAsc);
					})
				->paginate(20);
    }

    public function show($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
		$this->validate($request,[
			'code' => 'required',
			'name' => 'required',
			'address' => 'required',
			'city' => 'required',
			'phone' => 'required',
			'deadline' => 'required|numeric'
		]);
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return $supplier;
    }

    public function store(Request $request)
    {
		$this->validate($request,[
			'code' => 'required',
			'name' => 'required',
			'address' => 'required',
			'city' => 'required',
			'phone' => 'required',
			'deadline' => 'required|numeric'
		]);
        $supplier = Supplier::create($request->all());
        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->deleted = true;
		$supplier->save();			
        return '';
    }

    public function code($code)
    {
    	if(strlen($code)>1){
    		return Supplier::where('deleted','=',false)->where('code','like','%'.$code.'%')->get();
    	}
    }

    public function search($searchVar)
    {
        if(strlen($searchVar)>1){
            return Supplier::where('deleted','=',false)->where('name','like','%'.$searchVar.'%')->get();
        }
    }
}
