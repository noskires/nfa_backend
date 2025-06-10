<?php

namespace App\Http\Controllers;

use App\Models\Acu;
use App\Http\Requests\StoreAcuRequest;
use App\Http\Requests\UpdateAcuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

class AcuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function allDataTables(Request $request)
    {
        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            // $resp = auth()->userOrFail();

            // $resp = Acu::select('*');

            $resp = Acu::select(
                'acus.id',
                'acus.code',
                'acus.capacity',
                'acus.type',
                'acus.installation_type',
                'acus.operation_type',
                // 'acus.serial_no',
                // 'acus.index_no',
                'acus.model',
                'acus.brand',
                // 'acus.maintainer',
                // 'acus.status',
                'acus.date_installed',
                'acus.date_accepted',
                'acus.manufacturer_id',
                'manufacturer.name AS manufacturer_name',
                'acus.site_id',
                'site.name AS site_name',
            )
            ->leftjoin('lib_manufacturers AS manufacturer','manufacturer.id','=','acus.manufacturer_id')
            ->leftjoin('sites AS site','site.id','=','acus.site_id')
            ;

            $dtables = DataTables::eloquent($resp)

            // ->filterColumn('site_name', function($query, $keyword) {
            //     $sql = "site.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            // ->filterColumn('manufacturer_name', function($query, $keyword) {
            //     $sql = "manufacturer.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            ;

            return $dtables->toJson();

            // return DataTables::of($resp)->make(true);

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // public function allSelect2(Request $request){ 

    //     $data = array(
    //         'id'=>$request->input('id'),
    //         'search'=>$request->input('search'),//select2 default
    //         'q'=>$request->input('q'),
    //     );

    //     $training_code = null;
        
    //     $collection = Rectifier::select(
    //         'rectifiers.id AS id',
    //         // DB::raw("CONCAT(site.code,'RE',LPAD(rectifiers.index_no,3,0),'-',site.name) AS text"),
    //         DB::raw("CONCAT(site.code,'RE',manufacturer.code,LPAD(rectifiers.index_no,3,0),'-',site.name) AS text"),
    //     )
    //     ->leftjoin('lib_manufacturers AS manufacturer','manufacturer.id','=','rectifiers.manufacturer_id')
    //     ->leftjoin('sites AS site','site.id','=','rectifiers.site_id')
    //     ;

    //     if($data['search']){
    //         // $collection = $collection->where(DB::raw("CONCAT(site.name,'RE', manufacturer.code)"), 'like', '%'.$data['search'].'%');
    //         $collection = $collection->where(DB::raw("CONCAT(site.code,'RE',manufacturer.code,LPAD(rectifiers.index_no,3,0),'-',site.name)"), 'like', '%'.$data['search'].'%');
    //     }

    //     $collection = $collection->orderBy(DB::raw("CONCAT(site.code,'RE',manufacturer.code,LPAD(rectifiers.index_no,3,0),'-',site.name)"),  'asc');

    //     $query = $collection;
        
    //     $collection = $collection->get(); 

    //     return response()->json([
    //         'status'=>200,
    //         'results'=>$collection,
    //     ]);

    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcuRequest $request)
    {
        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Acu;
                $resp->code                    = "ACU-".(string) Str::uuid();
                $resp->site_id                  = $fields['site_id'];
                $resp->manufacturer_id          = $fields['manufacturer'];
                // $resp->serial_no                = $fields['serial_no'];
                // $resp->index_no                 = $fields['index_no'];
                $resp->capacity                 = $fields['capacity'];
                $resp->type                     = $fields['type'];
                $resp->model                    = $fields['model'];
                $resp->brand                    = $fields['brand'];
                $resp->installation_type        = $fields['installation_type'];
                $resp->operation_type           = $fields['operation_type'];
                $resp->date_installed           = $fields['date_installed'];
                $resp->date_accepted            = $fields['date_accepted'];
                
                // $resp->created_by               = Auth::user()->email;
                // $resp->changed_by               = Auth::user()->email;
                $resp->save();
                
                return response()->json([
                    'status' => 200,
                    'data' => null,
                    'message' => 'Successfully saved.'
                ]);

        //     }
        //     catch (\Exception $e) 
        //     {
        //         return response()->json([
        //             'status' => 500,
        //             'data' => null,
        //             'message' => 'Error, please try again!'
        //         ]);
        //     }
        // });

        return $transaction;
    }

    /**
     * Display the specified resource.
     */
    public function show(Acu $acu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acu $acu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcuRequest $request, Acu $acu)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Acu::where('id', $fields['id'])->first();
            // $resp->code                  = $fields['code'];
            $resp->site_id                  = $fields['site_id'];
            $resp->manufacturer_id          = $fields['manufacturer'];
            // $resp->serial_no             = $fields['serial_no'];
            // $resp->index_no              = $fields['index_no'];
            $resp->type                     = $fields['type'];
            $resp->model                    = $fields['model'];
            $resp->brand                    = $fields['brand'];
            $resp->capacity                 = $fields['capacity'];
            $resp->installation_type        = $fields['installation_type'];
            $resp->operation_type           = $fields['operation_type'];
            $resp->date_installed           = $fields['date_installed'];
            $resp->date_accepted            = $fields['date_accepted'];
            
            // $resp->changed_by               = Auth::user()->email;
            $resp->save();

            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Successfully updated.'
            ]);

            
        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acu $acu, Request $request)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Acu::where('id', $fields['id'])->firstOrFail()->delete();

	        return response()->json([
	            'status' => 200,
	            'data' => 'null',
	            'message' => 'Successfully deleted.'
	        ]);

	    //   }
	    //   catch (\Exception $e) 
	    //   {
	    //       return response()->json([
	    //         'status' => 500,
	    //         'data' => 'null',
	    //         'message' => 'Error, please try again!'
	    //     ]);
	    //   }
	    // });

   	 	return $transaction;
    }
}
