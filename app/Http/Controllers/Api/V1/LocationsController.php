<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Location;

class LocationsController extends Controller
{
    public function index()
    {
        $keywordCode = isset($_GET['searchVarCode']) ? $_GET['searchVarCode'] : NULL;
        $keywordName = isset($_GET['searchVarName']) ? $_GET['searchVarName'] : NULL;
        $keywordAddress = isset($_GET['searchVarAddress']) ? $_GET['searchVarAddress'] : NULL;
        $keywordCity = isset($_GET['searchVarCity']) ? $_GET['searchVarCity'] : NULL;
        $keywordPhone = isset($_GET['searchVarPhone']) ? $_GET['searchVarPhone'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        return Location::where('deleted','=',false)
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
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

    public function show($id)
    {
        return Location::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]);
        $location = Location::findOrFail($id);
        $location->update($request->all());

        return $location;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone' => 'required',
        ]);
        $location = Location::create($request->all());
        return $location;
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->deleted = true;
        $location->save();          
        return '';
    }


    public function search($searchVar)
    {
        if(strlen($searchVar)>1){
            return Location::where('deleted','=',false)->where('name','like','%'.$searchVar.'%')->get();
        }
    }
}
