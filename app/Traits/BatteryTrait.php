<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Battery;
use DB;

trait BatteryTrait
{
    function perCapacity($data){

        $regions = array(
            array("id"=>10000000, "code"=>"nemm", "text"=>"NEMM", "alias"=>"nemm"),
            array("id"=>20000000, "code"=>"swmm", "text"=>"SWMM", "alias"=>"swmm"),
            array("id"=>30000000, "code"=>"nl", "text"=>"NL", "alias"=>"nl"),
            array("id"=>40000000, "code"=>"sl", "text"=>"SL", "alias"=>"sl"),
            array("id"=>50000000, "code"=>"vis", "text"=>"VIS", "alias"=>"vis"),
            array("id"=>60000000, "code"=>"min", "text"=>"MIN", "alias"=>"min"),
        );

        $site_categories = array(
            array("id"=>"1", "code"=>"copurecore", "name"=>"CO-PureCore", "alias"=>""),
            array("id"=>"2", "code"=>"comixed", "name"=>"CO-Mixed", "alias"=>""),
            array("id"=>"3", "code"=>"npob", "name"=>"NPOB", "alias"=>""),
            array("id"=>"4", "code"=>"cs", "name"=>"CS", "alias"=>""),
            array("id"=>"5", "code"=>"bts", "name"=>"BTS", "alias"=>""),
        );

        $battery_types = array(
            array("id"=>"1", "code"=>"lib", "name"=>"LIB", "alias"=>""),
            array("id"=>"2", "code"=>"vrla", "name"=>"VRLA", "alias"=>""),
        );
        
        $data = Battery::select(
            'battery_site.area',
            'organization.alias as area_name',
            'type',
            'capacity',
            DB::raw("
                CASE
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 0 AND 5 then '0 - 5 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 6 AND 10 then '6 - 10 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 11 AND 100 then 'More than 10 Year'
                ELSE 'No Date Installed info'
                END as age_from_date_installed_group
            "),
            DB::raw("count(*) as count"),
        )->groupBy(
            'battery_site.area',
            'organization.alias',
            'type',
            'capacity',
            DB::raw("
                CASE
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 0 AND 5 then '0 - 5 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 6 AND 10 then '6 - 10 Years'
                    WHEN YEAR(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(batteries.date_installed))) BETWEEN 11 AND 100 then 'More than 10 Year'
                ELSE 'No Date Installed info'
                END
            "),
        )
        ->leftjoin('sites AS battery_site','battery_site.id','=','batteries.site_id')
        ->leftjoin('lib_organizations AS organization','organization.code','=','battery_site.area')
        ->get();

        $fields['data'] = $data;
        
        // foreach($data as $key=>$datum){
        //     $fields['fields'] = $datum['count'];
        // }

        foreach($regions as $key=>$region){

            foreach($battery_types as $key=>$battery_type){
                $fields['region'][$region['code']][$battery_type['code']] = Battery::WHERE('type', $battery_type['id'])
                                                                            ->WHERE('battery_site.area', '30000000')
                                                                            ->leftjoin('sites AS battery_site','battery_site.id','=','batteries.site_id')
                                                                            ->count();
                // $fields['region']["nationwide"][$battery_type['code']] = Battery::WHERE('type', $battery_type['id'])->count();
            }

            // $fields['region'][$region['code']]["total"] = Battery::WHERE('area', $region['id'])->count();
            // $fields['region']["nationwide"]["total"] = Battery::count();
        }

        return $fields;

    }
}
