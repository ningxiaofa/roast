<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Request;
use App\Models\Cafe;
use App\Http\Requests\StoreCafeRequest;
use App\Utilities\GaodeMaps;

class CafesController extends Controller
{
    /*
         |-------------------------------------------------------------------------------
         | Get All Cafes
         |-------------------------------------------------------------------------------
         | URL:            /api/v1/cafes
         | Method:         GET
         | Description:    Gets all of the cafes in the application
        */
    public function getCafes(){
        $cafes = Cafe::all();
        return response()->json($cafes);
    }

    /*
     |-------------------------------------------------------------------------------
     | Get An Individual Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes/{id}
     | Method:         GET
     | Description:    Gets an individual cafe
     | Parameters:
     |   $id   -> ID of the cafe we are retrieving
    */
    public function getCafe($id){
        $cafe = Cafe::where('id', '=', $id)->first();
        return response()->json($cafe);
    }

    /*
     |-------------------------------------------------------------------------------
     | Adds a New Cafe
     |-------------------------------------------------------------------------------
     | URL:            /api/v1/cafes
     | Method:         POST
     | Description:    Adds a new cafe to the application
    */
    public function postNewCafe(StoreCafeRequest $request){
        //为了简化业务逻辑，这里假定用户提交的数据都是可靠的，将数据验证逻辑省略掉
        $cafe = new Cafe();

        //cafes 表的其他字段都是动态添加的，无需用户手动提交。关于经度和纬度字段的获取，后续在地图 API 中会提到.
        $cafe->name     = $request->input('name');  // Request::get('name'); 亦可
        $cafe->address  = $request->input('address');
        $cafe->city     = $request->input('city');
        $cafe->state    = $request->input('state');
        $cafe->zip      = $request->input('zip');
        //处理经纬度
        $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
        $cafe->latitude = $coordinates['lat'];
        $cafe->longitude = $coordinates['lng'];

        $cafe->save();

        return response()->json($cafe, 201); ///遵循 RESTful 原则返回 201 状态码，表示实体已创建
    }
}