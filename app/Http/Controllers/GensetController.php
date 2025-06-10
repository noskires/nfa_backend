<?php

namespace App\Http\Controllers;

use App\Models\Genset;
use App\Http\Requests\StoreGensetRequest;
use App\Http\Requests\UpdateGensetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

class GensetController extends Controller
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

            $resp = Genset::select(
                'gensets.id',
                'gensets.code',
                'gensets.capacity',
                'gensets.rating',
                'gensets.percent_utilization',
                'gensets.type',
                'gensets.status',
                'gensets.model',
                'gensets.brand',
                'gensets.owner',
                'gensets.date_manufactured',
                'gensets.date_installed',
                'gensets.date_accepted',
                'gensets.manufacturer_id',
                'manufacturer.name AS manufacturer_name',
                'gensets.site_id',
                'site.name AS site_name',
                'organization.alias as area_name',
            )
            ->leftjoin('lib_manufacturers AS manufacturer','manufacturer.id','=','gensets.manufacturer_id')
            ->leftjoin('sites AS site','site.id','=','gensets.site_id')
            ->leftjoin('lib_organizations AS organization','organization.code','=','site.area')
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
    public function store(StoreGensetRequest $request)
    {
        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Genset;
                $resp->code                    = "GEN-".(string) Str::uuid();
                $resp->site_id                  = $fields['site_id'];
                $resp->manufacturer_id          = $fields['manufacturer'];
                $resp->capacity                 = $fields['capacity'];
                $resp->rating                   = $fields['rating'];
                $resp->type                     = $fields['type'];
                $resp->model                    = $fields['model'];
                $resp->brand                    = $fields['brand'];
                $resp->status                   = $fields['status'];
                $resp->percent_utilization      = $fields['percent_utilization'];
                $resp->owner                    = $fields['owner'];
                $resp->date_manufactured        = $fields['date_manufactured'];
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
    public function show(Genset $genset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genset $genset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGensetRequest $request, Genset $genset)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Genset::where('id', $fields['id'])->first();
            $resp->site_id                  = $fields['site_id'];
            $resp->manufacturer_id          = $fields['manufacturer'];
            $resp->capacity                 = $fields['capacity'];
            $resp->rating                   = $fields['rating'];
            $resp->type                     = $fields['type'];
            $resp->model                    = $fields['model'];
            $resp->brand                    = $fields['brand'];
            $resp->status                   = $fields['status'];
            $resp->percent_utilization      = $fields['percent_utilization'];
            $resp->owner                    = $fields['owner'];
            $resp->date_manufactured        = $fields['date_manufactured'];
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
    public function destroy(Genset $genset, Request $request)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Genset::where('id', $fields['id'])->firstOrFail()->delete();

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
