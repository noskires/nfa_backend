<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DcPanel extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "dc_panels";

    public function rectifier(){
    	return $this->hasOne('App\Models\Rectifier', 'id', 'rectifier_id');
    }
    
    public function scopeDefaultFields($query){
    	
        $query->select(
            'dc_panels.id',
            'dc_panels.rectifier_id',
            'dc_panels.code',
            'dc_panels.manufacturer_id',
            'dc_panels.index_no',
            'dc_panels.model',
            'dc_panels.maintainer',
            'dc_panels.status',
            'dc_panels.date_installed',
            'dc_panels.date_accepted',
            'dc_panels.fuse_breaker_number',
            'dc_panels.fuse_breaker_rating',
            'dc_panels.feed_source',
            'dc_panels.no_of_runs_and_cable_size',
            'dc_panels.source_voltage',
            'dc_panels.source_electric_current',
            'dc_panels.status_of_breakers',
            'dc_panels.remarks',
            'lib_manufacturers.name AS manufacturer_name',
            DB::raw("CONCAT(sites.code,'DC',lib_manufacturers.code,LPAD(dc_panels.index_no,3,0)) AS dc_panel_name"),
        )
        ->leftJoin('lib_manufacturers', function($join){
          $join->on('lib_manufacturers.id', '=', 'dc_panels.manufacturer_id');
        })
        ->leftJoin('rectifiers', function($join){
            $join->on('rectifiers.id', '=', 'dc_panels.rectifier_id');
        })
        ->leftJoin('sites', function($join){
            $join->on('sites.id', '=', 'rectifiers.site_id');
        })
        ;
    }
}
