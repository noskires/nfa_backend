<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Http\Requests\StoreBatteryRequest;
use App\Http\Requests\UpdateBatteryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

use App\Exports\BatteryExport;
use Maatwebsite\Excel\Facades\Excel;

// Traits
use App\Traits\BatteryTrait;

class BatteryController extends Controller
{
    use BatteryTrait;
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $model = Battery::query();
        $dtatables = DataTables::eloquent($model);
        return $dtatables->toJson();
    }

    public function allDataTables(Request $request)
    {

        $data = array(
            'code'=>$request->input('code'),
        );

    	try {
            // $resp = auth()->userOrFail();

            // $resp = Battery::select('*');

            $resp = Battery::select(
                'batteries.id',
                'batteries.code',
                'batteries.index_no',
                'batteries.bank',
                'batteries.model',
                'batteries.maintainer',
                'batteries.status',
                'batteries.date_manufactured',
                // DB::raw("YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_manufactured))) as age_from_date_manufactured"),
                // DB::raw("
                //     CASE
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_manufactured))) BETWEEN 0 AND 5  then '0 - 5 Years'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_manufactured))) BETWEEN 6 AND 10  then '6 - 10 Years'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_manufactured))) BETWEEN 11 AND 15  then '11 - 15 Year'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_manufactured))) BETWEEN 16 AND 15  then 'More than 15 Year'
                //     END as age_from_date_manufactured_group
                // "),
                'batteries.date_installed',
                DB::raw("YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) as age_from_date_installed"),
                DB::raw("
                    (CASE
                        WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 0 AND 5  then '0 - 5 Years'
                        WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 6 AND 10  then '6 - 10 Years'
                        WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 11 AND 15  then '11 - 15 Year'
                        WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) > 15  then 'More than 15 Year'
                    ELSE 'No Date Installed info'
                    END) as age_from_date_installed_group
                "),
                'batteries.date_accepted',
                // DB::raw("YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_accepted))) as age_from_date_accepted"),
                // DB::raw("
                //     CASE
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_accepted))) BETWEEN 0 AND 5  then '0 - 5 Years'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_accepted))) BETWEEN 6 AND 10  then '6 - 10 Years'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_accepted))) BETWEEN 11 AND 15  then '11 - 15 Year'
                //     WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_accepted))) BETWEEN 16 AND 15  then 'More than 15 Year'
                //     END as age_from_date_accepted_group
                // "),
                'batteries.capacity',
                'batteries.type',
                'batteries.brand',
                'batteries.individual_cell_voltage',
                'batteries.no_of_cells',
                'batteries.cell_status',
                'batteries.cable_size',
                'batteries.backup_time',
                'batteries.float_voltage_requirement',
                'batteries.remarks',
                'batteries.rectifier_id',
                // DB::raw("CONCAT(rec_site.code,'RE',LPAD(rectifier.index_no,3,0),'-',rec_site.name) AS rectifier_name"),
                DB::raw("CONCAT(rec_site.code,'RE',rectifier_manufacturer.code,LPAD(rectifier.index_no,3,0),'-',rec_site.name) AS rectifier_name"), //orig field
                // DB::raw("CONCAT(rec_site.code,'BA',battery_manufacturer.code,LPAD(batteries.index_no,3,0)) AS battery_name"),
                // 'rectifier.site_id AS rec_site_id',
                'batteries.site_id AS battery_site_id',
                'battery_site.name AS site_name',
                // 'batteries.manufacturer_id AS battery_manufacturer_id',
                'battery_manufacturer.name AS battery_manufacturer_name',
                'organization.alias as area_name',
                
            )
            ->leftjoin('rectifiers AS rectifier','rectifier.id','=','batteries.rectifier_id')
            ->leftjoin('lib_manufacturers AS battery_manufacturer','battery_manufacturer.id','=','batteries.manufacturer_id')
            ->leftjoin('lib_manufacturers AS rectifier_manufacturer','rectifier_manufacturer.id','=','rectifier.manufacturer_id')
            ->leftjoin('sites AS rec_site','rec_site.id','=','rectifier.site_id')
            ->leftjoin('sites AS battery_site','battery_site.id','=','batteries.site_id')
            ->leftjoin('lib_organizations AS organization','organization.code','=','battery_site.area')
            ;

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('rectifier_name', function($query, $keyword) {
                $sql = "CONCAT(rec_site.code,'RE',rectifier_manufacturer.code,LPAD(rectifier.index_no,3,0),'-',rec_site.name) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('battery_site_id', function($query, $keyword) {
                $sql = "batteries.site_id like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('site_name', function($query, $keyword) {
                $sql = "battery_site.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            // ->filterColumn('battery_manufacturer_id', function($query, $keyword) {
            //     $sql = "batteries.manufacturer_id like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })

            ->filterColumn('battery_manufacturer_name', function($query, $keyword) {
                $sql = "battery_manufacturer.name like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('area_name', function($query, $keyword) {
                $sql = "organization.alias like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('age_from_date_installed', function($query, $keyword) {
                $sql = "YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })

            ->filterColumn('age_from_date_installed_group', function($query, $keyword) {
                $sql = "(CASE
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 0 AND 5  then '0 - 5 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 6 AND 10  then '6 - 10 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 11 AND 15  then '11 - 15 Year'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) > 15  then 'More than 15 Year'
                    END) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ;

            return $dtables->toJson();

            // return DataTables::of($resp)->make(true);

        } catch(JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // public function index3(Request $request)
    // {
    //     // return $request['q'];
    //     // return Battery::select('id', 'code')->get();
    //     return array('1','5','9');
    // }

    public function allSelect2(Request $request){ 

        // return $request->all();

        $data = array(
            'id'=>$request->input('id'),
            'search'=>$request->input('search'),//select2 default
            'q'=>$request->input('q'),//select2 default
        );

        $collection = Battery::select(
            'id AS id',
            // 'name AS text',
            DB::raw("CONCAT(id, '-', code) as text"),
        );

        if($data['search']){
            $collection = $collection->where(
                DB::raw("CONCAT(id, '-', code) ")
                , 'like', '%'.$data['search'].'%');
        }

        if($data['q']){
            $collection = $collection->where(
                DB::raw("CONCAT(id, '-', code) ")
                , 'like', '%'.$data['q'].'%');
        }

        $query = $collection;

        return $collection = $collection->get(); 

        // return response()->json([
        //     'status'=>200,
        //     'results'=>$collection,
        // ]);

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
    public function store(StoreBatteryRequest $request)
    {

        // Battery::create([
        //     'code'=> $request->code,
        //     'rectifier_id'=> $request->rectifier_id,
        //     'brand'=> $request->brand,
        //     'type'=> $request->type,
        // ])->save();

        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Battery;
                $resp->code                 = "BAT-".(string) Str::uuid();
                $resp->site_id              = $fields['site_id'];
                $resp->manufacturer_id      = $fields['manufacturer'];
                $resp->bank                 = $fields['bank'];
                $resp->rectifier_id         = $fields['rectifier'];
                $resp->index_no             = $fields['index_no'];
                $resp->model                = $fields['model'];
                $resp->maintainer           = $fields['maintainer'];
                $resp->status               = $fields['status'];
                $resp->date_installed       = $fields['date_installed'];
                $resp->date_accepted        = $fields['date_accepted'];
                $resp->capacity             = (int)$fields['capacity'];
                $resp->type                 = $fields['type'];
                $resp->brand                = $fields['brand'];
                $resp->individual_cell_voltage = $fields['individual_cell_voltage'];
                $resp->no_of_cells          = $fields['no_of_cells'];
                $resp->cell_status          = $fields['cell_status'];
                $resp->cable_size           = $fields['cable_size'];
                $resp->backup_time          = $fields['backup_time'];
                $resp->float_voltage_requirement = $fields['float_voltage_requirement'];
                $resp->remarks              = $fields['remarks'];
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
    public function show(Battery $battery)
    {
        return $battery;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Battery $battery)
    {
        return $battery;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBatteryRequest $request, Battery $battery)
    {
        // $battery->update([
        //     'brand'=> $request->brand,
        //     'type'=> $request->type,
        // ]);

        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Battery::where('id', $fields['id'])->first();
            $resp->site_id              = $fields['site_id'];
            $resp->manufacturer_id      = $fields['manufacturer'];
            $resp->bank                 = $fields['bank'];
            $resp->rectifier_id         = $fields['rectifier'];
            $resp->index_no             = $fields['index_no'];
            $resp->model                = $fields['model'];
            $resp->maintainer           = $fields['maintainer'];
            $resp->status               = $fields['status'];
            $resp->date_installed       = $fields['date_installed'];
            $resp->date_accepted        = $fields['date_accepted'];
            $resp->capacity             = $fields['capacity'];
            $resp->type                 = $fields['type'];
            $resp->brand                = $fields['brand'];
            $resp->individual_cell_voltage = $fields['individual_cell_voltage'];
            $resp->no_of_cells          = $fields['no_of_cells'];
            $resp->cell_status          = $fields['cell_status'];
            $resp->cable_size           = $fields['cable_size'];
            $resp->backup_time          = $fields['backup_time'];
            $resp->float_voltage_requirement = $fields['float_voltage_requirement'];
            $resp->remarks              = $fields['remarks'];
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
    public function destroy(Battery $battery, Request $request)
    {

        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Battery::where('id', $fields['id'])->firstOrFail()->delete();

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

    public function summary(Request $request)
    {
        return $this->perCapacity($request);
    }

    public function export() 
    {   
        return Excel::download(new BatteryExport, 'NeworkElements.xlsx');
    }
}
