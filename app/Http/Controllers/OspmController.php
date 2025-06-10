<?php

namespace App\Http\Controllers;

use App\Models\Ospm;
use App\Http\Requests\StoreOspmRequest;
use App\Http\Requests\UpdateOspmRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use DataTables;
use DB;

use App\Exports\OspmExport;
use Maatwebsite\Excel\Facades\Excel;

// Traits
use App\Traits\OspmTrait;

class OspmController extends Controller
{
    use OspmTrait;

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
            // $resp = auth()->userOrFail();

            // $resp = Ospm::select('*');
            $resp = Ospm::select(
                'ospm.id',
                'ospm.work_order_no',
                'ospm.status',
                'ospm.severity',
                'ospm.company',
                'ospm.region',
                'ospm.division',
                'ospm.section',
                'ospm.contractor',
                'ospm.sub_contractor',
                'ospm.endorsed_at',
                'ospm.service_restored_at',
                'ospm.full_link_restored_at',
                'ospm.remarks',
                // DB::raw("((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60) as mttr_service_restored2"),
                // DB::raw("((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))) as mttr_service_restored1"),
                // DB::raw("((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60) as mttr_full_link_restored"),
                DB::raw("(CASE WHEN ospm.service_restored_at IS NULL THEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)
                    ELSE 
                        ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)
                    END) AS mttr_service_restored
                "),
                DB::raw("
                    (CASE WHEN ospm.service_restored_at IS NULL THEN 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END
                    ELSE 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END

                    END) AS mttr_service_restored_sla
                "),
                DB::raw("(CASE WHEN ospm.full_link_restored_at IS NULL THEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)
                    ELSE 
                        ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)
                    END) AS mttr_full_link_restored
                "),
                DB::raw("
                    (CASE WHEN ospm.full_link_restored_at IS NULL THEN 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END
                    ELSE 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END

                    END) AS mttr_full_link_restored_sla
                "),

                DB::raw("
                    (CASE WHEN ospm.service_restored_at IS NULL THEN 
                        TIMESTAMPDIFF(DAY, endorsed_at, NOW())
                    ELSE 
                       TIMESTAMPDIFF(DAY, endorsed_at, service_restored_at)
                    END) AS ageing_service_restored
                "),

                DB::raw("
                    (CASE WHEN ospm.service_restored_at IS NULL THEN 
                        CASE 
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))>20160 THEN '>2Weeks'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))>10080 THEN '>7Days'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))>4320 THEN '>3Days'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))>1440 THEN '>1Day'
                        ELSE 
                            '0-24H' 
                        END
                    ELSE 
                       CASE 
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))>20160 THEN '>2Weeks'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))>10080 THEN '>7Days'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))>4320 THEN '>3Days'
                            WHEN (TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))>1440 THEN '>1Day'
                        ELSE 
                            '0-24H' 
                        END
                    END) AS ageing_service_restored_group
                "),

                // Total Minutes 	 Category 
                // 1 	0-24H
                // 1,441 	>1Day
                // 4,321 	>3Days
                // 10,081 	>7Days
                // 20,161 	>2Weeks


                // CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW())))<1441 THEN '0-24H'
                // CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW())))<4321 THEN '>1Day'
                // CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW())))<10081 THEN '>3Days'
                // CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW())))<20161 THEN '>7Days'
                // ELSE '2Weeks' END
               
                
            );

            $dtables = DataTables::eloquent($resp)

            ->filterColumn('mttr_service_restored', function($query, $keyword) {
                $sql = "(CASE WHEN ospm.service_restored_at IS NULL THEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)
                    ELSE 
                        ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)
                    END) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('mttr_service_restored_sla', function($query, $keyword) {
                $sql = "(CASE WHEN ospm.service_restored_at IS NULL THEN 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END
                    ELSE 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END

                    END) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('mttr_full_link_restored', function($query, $keyword) {
                $sql = "(CASE WHEN ospm.full_link_restored_at IS NULL THEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)
                    ELSE 
                        ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)
                    END) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('mttr_full_link_restored_sla', function($query, $keyword) {
                $sql = "(CASE WHEN ospm.full_link_restored_at IS NULL THEN 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END
                    ELSE 
                        CASE WHEN ospm.severity = 'SA' THEN 
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)>4 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        ELSE
                            CASE WHEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, full_link_restored_at))/60)>8 THEN 'BEYOND SLA'
                            ELSE 'WITHIN SLA'
                            END
                        END

                    END) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ;
            
            return $dtables->toJson();

            // return DataTables::of($resp)->make(true);

        // } catch(JWTException $e) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    }

    // public function allSelect2(Request $request){ 

    //     $data = array(
    //         'id'=>$request->input('id'),
    //         'search'=>$request->input('search'),//select2 default
    //     );

        
    //     $collection = Subdomain::select(
    //         'id',
    //         'name as text',
    //     );

    //     if($data['search']){
    //         $collection = $collection->where('name', 'like', '%'.$data['search'].'%');
    //     }

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
    public function store(StoreOspmRequest $request)
    {
        $fields = $request->all();
        
        // $transaction = DB::transaction(function($field) use($fields){
        //     try{

                $resp = new Ospm;
                $resp->work_order_no            = $fields['work_order_no'];
                $resp->status                   = $fields['status'];
                $resp->severity                 = $fields['severity'];
                $resp->company                  = $fields['company'];
                $resp->region                   = $fields['region'];
                $resp->division                 = $fields['division'];
                $resp->section                  = $fields['section'];
                $resp->contractor               = $fields['contractor'];
                $resp->sub_contractor           = $fields['sub_contractor'];
                $resp->endorsed_at              = $fields['endorsed_at'];
                $resp->service_restored_at      = $fields['service_restored_at'];
                $resp->full_link_restored_at    = $fields['full_link_restored_at'];
                $resp->remarks                  = $fields['remarks'];
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
    public function show(SubDomain $subDomain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubDomain $subDomain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOspmRequest $request, Ospm $ospm)
    {
        $fields = $request->all();

        // $transaction = DB::transaction(function($field) use($fields){
        // try{

            $resp = Ospm::where('id', $fields['id'])->first();
            $resp->work_order_no            = $fields['work_order_no'];
            $resp->status                   = $fields['status'];
            $resp->severity                 = $fields['severity'];
            $resp->company                  = $fields['company'];
            $resp->region                   = $fields['region'];
            $resp->division                 = $fields['division'];
            $resp->section                  = $fields['section'];
            $resp->contractor               = $fields['contractor'];
            $resp->sub_contractor           = $fields['sub_contractor'];
            $resp->endorsed_at              = $fields['endorsed_at'];
            $resp->service_restored_at      = $fields['service_restored_at'];
            $resp->full_link_restored_at    = $fields['full_link_restored_at'];
            $resp->remarks                  = $fields['remarks'];
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
    public function destroy(Ospm $ospm, Request $request)
    {
        $fields = $request->all();

	    // $transaction = DB::transaction(function($field) use($fields){
	    // try{

			Ospm::where('id', $fields['id'])->firstOrFail()->delete();

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
        return Excel::download(new OspmExport, 'Ospm.xlsx');
    }

    
    public function summary(Request $request)
    {
        return $this->perRegion($request);
    }
    
}
