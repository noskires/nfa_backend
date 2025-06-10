<?php

namespace App\Http\Controllers;

use App\Models\SiteCategory;
use App\Http\Requests\StoreSiteCategoryRequest;
use App\Http\Requests\UpdateSiteCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

class SiteCategoryController extends Controller
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

            $resp = SiteCategory::select('*');

            return DataTables::of($resp)->make(true);

        // } catch(JWTException $e) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    }

    public function allSelect2(Request $request){ 

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
        );

        
        $collection = SiteCategory::select(
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
    public function store(StoreSiteCategoryRequest $request)
    {
        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new SiteCategory;
                $resp->code             = "STC-".(string) Str::uuid();
                $resp->name             = $fields['name'];
                $resp->description      = $fields['description'];
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
    public function show(SiteCategory $siteCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteCategory $siteCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteCategoryRequest $request, SiteCategory $siteCategory)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = SiteCategory::where('id', $fields['id'])->first();
            $resp->name             = $fields['name'];
            // $resp->description      = $fields['description'];
            // $resp->changed_by       = Auth::user()->email;
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
    public function destroy(SiteCategory $siteCategory, Request $request)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			SiteCategory::where('id', $fields['id'])->firstOrFail()->delete();

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
