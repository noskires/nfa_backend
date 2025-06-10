<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function allDataTables(Request $request){

        $data = array(
            'code'=>$request->input('code'),
        );

    	// try {
        //     $resp = auth()->userOrFail();

            // $resp = Rectifier::select('*');

            $resp = Rectifier::select(
                'rectifiers.id',
                'rectifiers.code',
                'rectifiers.serial_no',
                'rectifiers.index_no',
                'rectifiers.model',
                'rectifiers.maintainer',
                'rectifiers.status',
                'rectifiers.date_installed',
                'rectifiers.date_accepted',
                'rectifiers.rectifier_system_naming',
                'rectifiers.type',
                'rectifiers.brand',
                'rectifiers.no_of_existing_module',
                'rectifiers.no_of_slots',
                'rectifiers.capacity_per_module',
                'rectifiers.full_capacity',
                'rectifiers.dc_voltage',
                'rectifiers.total_actual_load',
                'rectifiers.percent_utilization',
                'rectifiers.external_alarm_activation',
                'rectifiers.no_of_runs_and_cable_size',
                'rectifiers.tvss_brand_rating',
                'rectifiers.rectifier_dc_breaker_brand',
                'rectifiers.rectifier_battery_slot',
                'rectifiers.dcpdb_equipment_load_assignment',
                'rectifiers.remarks',
                'rectifiers.manufacturer_id',
                'manufacturer.name AS manufacturer_name',
                'rectifiers.site_id',
                'site.name AS site_name',
            )
            ->leftjoin('lib_manufacturers AS manufacturer','manufacturer.id','=','rectifiers.manufacturer_id')
            ->leftjoin('sites AS site','site.id','=','rectifiers.site_id')
            ;

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('site_name', function($query, $keyword) {
                $sql = "site.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('manufacturer_name', function($query, $keyword) {
                $sql = "manufacturer.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ;

            return $dtables->toJson();

            // return DataTables::of($resp)->make(true);

        // } catch(JWTException $e) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    }

    public function allSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        $training_code = null;
        
        $collection = Manufacturer::select(
            'id',
            'name as text',
        );

        if($data['search']){
            $collection = $collection->where('name', 'like', '%'.$data['search'].'%');
        }

        $query = $collection;

        $collection = $collection->get(); 

        return response()->json([
            'status'=>200,
            'results'=>$collection,
        ]);

    }

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
    public function store(StoreManufacturerRequest $request)
    {
        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Manufacturer;
                // $resp->code             = "EXH-".(string) Str::uuid();
                $resp->code             = $fields['code'];
                $resp->name             = $fields['name'];
                // $resp->created_by       = Auth::user()->email;
                // $resp->changed_by       = Auth::user()->email;
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
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Manufacturer::where('id', $fields['id'])->first();
            $resp->code             = $fields['code'];
            $resp->name             = $fields['name'];
            $resp->changed_by       = Auth::user()->email;
            $resp->save();

            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Successfully updated.'
            ]);

        //   }
        //   catch (\Exception $e) 
        //   {
        //     return response()->json([
        //       'status' => 500,
        //       'data' => null,
        //       'message' => 'Error, please try again!'
        //     ]);
        //   }
        // });

        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Manufacturer::where('id', $fields['id'])->firstOrFail()->delete();

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
