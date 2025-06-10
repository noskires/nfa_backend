<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Ospm;
use DB;

trait OspmTrait
{
    function perRegion($data){

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
        
        $data = Ospm::select(
            'severity',
            'region',
            DB::raw("MONTH(endorsed_at) as month_endorsed"),
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

            DB::raw("count(*) as ticket_count"),
            DB::raw("
                SUM((CASE WHEN ospm.service_restored_at IS NULL THEN ((TIMESTAMPDIFF(MINUTE, endorsed_at, NOW()))/60)
                ELSE 
                    ((TIMESTAMPDIFF(MINUTE, endorsed_at, service_restored_at))/60)
                END)) AS total_mttr_service_restored
            "),
        
        )
        ->groupBy(
            'severity',
            'region',
            DB::raw("MONTH(endorsed_at)"),
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
                    END)
                "),
        )
        ->get();

        return $data;

    }
}