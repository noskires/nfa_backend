<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

use App\Exports\SiteExport;
use Maatwebsite\Excel\Facades\Excel;

// Traits
use App\Traits\SiteTrait;

class SiteController extends Controller
{
    use SiteTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing in datatable format.
     */

    public function allDefault(Request $request)
    {
        // return $request;
        $collection['site'] = Site::defaultFields()->whereFields($request)
        ->with(['rectifier'=>function($query) use ($request) {
            $query->defaultFields();
        }])
        ->with(['battery'=>function($query) use ($request) {
            $query->defaultFields();
        }])
        ->with(['network_element'=>function($query) use ($request) {
            $query->defaultFields();
        }])
        ->with(['acu'=>function($query) use ($request) {
            $query->defaultFields();
        }])
        ->with(['genset'=>function($query) use ($request) {
            $query->defaultFields();
        }])
        ->get();

        // return $collection;
        return response()->json([
            'status'=>200,
            'results'=>$collection,
        ]);
    }

    public function allDataTables()
    {
        // $model = Site::query();
        // $dtatables = DataTables::eloquent($model);
        // return $dtatables->toJson();

        // try {
        //     $resp = auth()->userOrFail();
            $resp = Site::select(
                'sites.id',
                'sites.code',
                'sites.name',
                'sites.area',
                'sites.status',
                'sites.site_category_id',
                'sc.name AS site_category_name',
                'sites.cabinet_type',
                'sites.region',
                'sites.province',
                'sites.city_municipality',
                'sites.brgy',
                'sites.street',
                'sites.lot_no',
                DB::raw("
                    CONCAT(
                        (CASE WHEN (sites.region IS NULL) THEN '' ELSE CONCAT(sites.region,', ') END),
                        (CASE WHEN (sites.province IS NULL) THEN '' ELSE CONCAT(sites.province,', ') END),
                        (CASE WHEN (sites.city_municipality IS NULL) THEN '' ELSE CONCAT(sites.city_municipality,', ') END),
                        (CASE WHEN (sites.brgy IS NULL) THEN '' ELSE CONCAT(sites.brgy,', ') END),
                        (CASE WHEN (sites.street IS NULL) THEN '' ELSE CONCAT(sites.street) END)
                    ) as address2
                "),

                DB::raw("CONCAT(COALESCE(region.name,''),', ', COALESCE(province.name,''),', ', COALESCE(city_municipality.name,''), ', ',COALESCE(brgy.name,''),', ',COALESCE(sites.street,'')) as address"),
                'sites.exchange_code',
                // 'exchange.name AS exchange_name',
                'sites.building_code',
                // 'building.name AS building_name',
                'sites.building_floor',
                // 'sites.equipment_room',
                // 'sites.room_existing_tag',
                'sites.longitude',
                'sites.latitude',
                'sites.electric_company_code',
                // 'ec.name AS electric_company_name',
                // 'ec.sin AS electric_company_sin',
                // 'ec.meter AS electric_company_meter',
                // 'sites.pss_owner_code',
                // 'po.name AS pss_owner_name',
                'sites.region as region_code',
                'region.name as region_name',
                'sites.province as province_code',
                'province.name as province_name',
                'sites.city_municipality as city_municipality_code',
                'city_municipality.name as city_municipality_name',
                'sites.brgy as brgy_code',
                'brgy.name as brgy_name',
                'organization.alias as area_name',
            )
        
            ->leftjoin('geo AS region','region.code','=','sites.region')
            ->leftjoin('geo AS province','province.code','=','sites.province')
            ->leftjoin('geo AS city_municipality','city_municipality.code','=','sites.city_municipality')
            ->leftjoin('geo AS brgy','brgy.code','=','sites.brgy')

            ->leftjoin('lib_site_categories AS sc','sc.id','=','sites.site_category_id')
            ->leftjoin('lib_organizations AS organization','organization.code','=','sites.area')
            // ->leftjoin('lib_buildings AS building','building.id','=','sites.building_code')
            // ->leftjoin('lib_exchanges AS exchange','exchange.id','=','sites.exchange_code')
            // ->leftjoin('lib_electric_companies AS ec','ec.id','=','sites.electric_company_code')
            // ->leftjoin('lib_pss_owners AS po','po.id','=','sites.pss_owner_code')
            ;

            // return DataTables::of($users)->make(true);
            // return User::all();
            $dtables = DataTables::eloquent($resp)

            ->filterColumn('category_name', function($query, $keyword) {
                $sql = "sc.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('address2', function($query, $keyword) {
                $sql = "CONCAT(
                    (CASE WHEN (sites.region IS NULL) THEN '' ELSE CONCAT(sites.region,', ') END),
                    (CASE WHEN (sites.province IS NULL) THEN '' ELSE CONCAT(sites.province,', ') END),
                    (CASE WHEN (sites.city_municipality IS NULL) THEN '' ELSE CONCAT(sites.city_municipality,', ') END),
                    (CASE WHEN (sites.brgy IS NULL) THEN '' ELSE CONCAT(sites.brgy,', ') END),
                    (CASE WHEN (sites.street IS NULL) THEN '' ELSE CONCAT(sites.street) END)
                ) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('area_name', function($query, $keyword) {
                $sql = "organization.alias like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('address', function($query, $keyword) {
                $sql = "CONCAT(COALESCE(region.name,''),', ', COALESCE(province.name,''),', ', COALESCE(city_municipality.name,''), ', ',COALESCE(brgy.name,''),', ',COALESCE(sites.street,'')) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            // ->filterColumn('building_name', function($query, $keyword) {
            //     $sql = "building.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            // ->filterColumn('exchange_name', function($query, $keyword) {
            //     $sql = "exchange.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            // ->filterColumn('electric_company_name', function($query, $keyword) {
            //     $sql = "ec.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            // ->filterColumn('electric_company_sin', function($query, $keyword) {
            //     $sql = "ec.sin like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            // ->filterColumn('electric_company_meter', function($query, $keyword) {
            //     $sql = "ec.meter like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            
            // ->filterColumn('pss_owner_name', function($query, $keyword) {
            //     $sql = "po.name like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            ;
            return $dtables->toJson();

        // } catch(JWTException $e) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    }

    public function allSelect2(Request $request){ 

        // return $request->all();

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
            'q'=>$request->input('q'),//select2 default
        );

        $collection = Site::select(
            'id AS id',
            // 'name AS text',
            DB::raw("CONCAT(code, '-', name) as text"),
        );

        if($data['search']){
            $collection = $collection->where(
                DB::raw("CONCAT(code, '-', name) ")
                , 'like', '%'.$data['search'].'%');
        }

        if($data['q']){
            $collection = $collection->where(
                DB::raw("CONCAT(code, '-', name) ")
                , 'like', '%'.$data['q'].'%');
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
    public function store(StoreSiteRequest $request)
    {

        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Site;
                $resp->code                 = $fields['code'];
                $resp->name                 = $fields['name'];
                $resp->area                 = $fields['area'];
                $resp->status               = $fields['status'];
                $resp->site_category_id     = $fields['site_category_id'];
                $resp->cabinet_type         = $fields['cabinet_type'];
                $resp->region               = $fields['region'];
                $resp->province             = $fields['province'];
                $resp->city_municipality    = $fields['city_municipality'];
                $resp->brgy                 = $fields['brgy'];
                $resp->street               = $fields['street'];
                $resp->lot_no               = $fields['lot_no'];
                $resp->longitude            = $fields['longitude'];
                $resp->latitude             = $fields['latitude'];
                // $resp->building_code        = $fields['building_code'];
                // $resp->exchange_code        = $fields['exchange_code'];
                // $resp->electric_company_code= $fields['electric_company_code'];
                // $resp->pss_owner_code       = $fields['pss_owner_code'];
                // $resp->created_by           = Auth::user()->email;
                // $resp->changed_by           = Auth::user()->email;
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
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Site::where('id', $fields['id'])->first();
            $resp->code                 = $fields['code'];
            $resp->name                 = $fields['name'];
            $resp->area                 = $fields['area'];
            $resp->status               = $fields['status'];
            $resp->site_category_id     = $fields['site_category_id'];
            $resp->cabinet_type         = $fields['cabinet_type'];
            $resp->region               = $fields['region'];
            $resp->province             = $fields['province'];
            $resp->city_municipality    = $fields['city_municipality'];
            $resp->brgy                 = $fields['brgy'];
            $resp->street               = $fields['street'];
            $resp->lot_no               = $fields['lot_no'];
            $resp->longitude            = $fields['longitude'];
            $resp->latitude             = $fields['latitude'];
            // $resp->building_code        = $fields['building_code'];
            // $resp->exchange_code        = $fields['exchange_code'];
            // $resp->electric_company_code= $fields['electric_company_code'];
            // $resp->pss_owner_code       = $fields['pss_owner_code'];
            // $resp->changed_by           = Auth::user()->email;
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
    public function destroy(Site $site, Request $request)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Site::where('id', $fields['id'])->firstOrFail()->delete();

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

    public function export() 
    {   
        return Excel::download(new SiteExport, 'sites.xlsx');
    }

    public function getSummaryPerSiteCategory(Request $request)
    {
        return $this->perSiteCategory($request);
    }

    public function getSummaryPerRegion(Request $request)
    {
        return $this->perRegion($request);
    }

}
