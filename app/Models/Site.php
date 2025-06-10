<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Site extends Model
{
    use HasFactory;
    protected $table = "sites";

    public function rectifier(){
        return $this->hasMany('App\Models\Rectifier', 'site_id', 'id');
    }

    public function battery(){
        return $this->hasMany('App\Models\Battery', 'site_id', 'id');
    }

    public function network_element(){
        return $this->hasMany('App\Models\NetworkElement', 'site_id', 'id');
    }

    public function acu(){
        return $this->hasMany('App\Models\Acu', 'site_id', 'id');
    }

    public function genset(){
        return $this->hasMany('App\Models\Genset', 'site_id', 'id');
    }

    public function scopeDefaultFields($query){
        $query->select(
            'sites.id',
            'sites.code',
            'sites.name',
            'sites.area',
            'sites.status',
            'sites.site_category_id',
            // 'sites.cabinet_type',
            // 'sites.region',
            // 'sites.province',
            // 'sites.city_municipality',
            // 'sites.brgy',
            // 'sites.street',
            // 'sites.lot_no',
            // 'sites.longitude',
            // 'sites.latitude',
            // 'sites.building_code',
            // 'sites.building_floor',
            // 'sites.exchange_code',
            // 'sites.electric_company_code',
            // 'sites.owner',
            'lib_site_categories.name AS site_category_name',
            'lib_organizations.alias AS area_name',
        )
        ->leftJoin('lib_site_categories', function($join){
          $join->on('lib_site_categories.id', '=', 'sites.site_category_id');
        })
        ->leftJoin('lib_organizations', function($join){
            $join->on('lib_organizations.code', '=', 'sites.area');
        })
        ;
    }

    public function scopeWhereFields($query, $data){
        if($data['site_id']){
            $query->where('sites.id', $data['site_id']);
        }
    }
}
