<?php

namespace App\Traits;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Site;
use DB;

trait SiteTrait
{
    function perSiteCategory($data){

        $fields['fields'] = $data;
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

        
        foreach($regions as $key=>$region){

            foreach($site_categories as $key=>$site_category){
                $fields['region'][$region['code']][$site_category['code']] = Site::WHERE('site_category_id', $site_category['id'])->WHERE('area', $region['id'])->count();
                $fields['region']["nationwide"][$site_category['code']] = Site::WHERE('site_category_id', $site_category['id'])->count();
            }

            $fields['region'][$region['code']]["total"] = Site::WHERE('area', $region['id'])->count();
            $fields['region']["nationwide"]["total"] = Site::count();
        }
        
        return $fields;
    }


    function perRegion($data){

        // $fields['fields'] = $data;
        // $regions = array(
        //     array("id"=>10000000, "text"=>"NEMM"),
        //     array("id"=>20000000, "text"=>"SWMM"),
        //     array("id"=>30000000, "text"=>"NL"),
        //     array("id"=>40000000, "text"=>"SL"),
        //     array("id"=>50000000, "text"=>"VIS"),
        //     array("id"=>60000000, "text"=>"MIN"),
        // );

        // $site_categories = array(
        //     array("id"=>"1", "name"=>"CO-PureCore"),
        //     array("id"=>"2", "name"=>"CO-Mixed"),
        //     array("id"=>"3", "name"=>"NPOB"),
        //     array("id"=>"4", "name"=>"CS"),
        //     array("id"=>"5", "name"=>"BTS"),
        // );

        // foreach($regions as $key=>$region){
        //     foreach($site_categories as $key=>$site_category){
        //         $fields['site_category'][$site_category['name']]['region'][$region['text']] = Site::WHERE('site_category_id', $site_category['id'])->WHERE('area', $region['id'])->count();
        //     }
        // }

    }

}
