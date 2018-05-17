<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;

class DepartmentsController extends Controller
{
    public function index()
    {
        $keywordCode = isset($_GET['searchVarCode']) ? $_GET['searchVarCode'] : NULL;
        $keywordName = isset($_GET['searchVarName']) ? $_GET['searchVarName'] : NULL;
        $orderWord = isset($_GET['orderVar']) ? $_GET['orderVar'] : NULL;
        $isAsc = isset($_GET['isAsc']) ? $_GET['isAsc'] : NULL;
        return Department::where('deleted','=',false)
                ->when($keywordCode, function ($query) use ($keywordCode){
                    $query->where('code','like','%'.$keywordCode.'%');
                    })
                ->when($keywordName, function ($query) use ($keywordName){
                    $query->where('name','like','%'.$keywordName.'%');
                    })
                ->when($orderWord, function ($query) use ($orderWord, $isAsc){
                    $query->orderBy($orderWord,$isAsc);
                    })
                ->paginate(20);
    }

    public function show($id)
    {
        return Department::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
        ]);
        $department = Department::findOrFail($id);
        $department->update($request->all());

        return $department;
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'code' => 'required',
            'name' => 'required',
        ]);
        $department = Department::create($request->all());
        return $department;
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->deleted = true;
        $department->save();          
        return '';
    }

    public function code($code)
    {
        if(strlen($code)>1){
            return Department::where('deleted','=',false)->where('code','like','%'.$code.'%')->get();
        }
    }
}
