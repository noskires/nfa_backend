<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkElement extends Model
{
    use HasFactory;
    protected $table = "network_elements";

    public function dcPanelItem(){
    	return $this->hasMany('App\Models\DcPanelItem', 'ne_id', 'id');
    }

    public function scopeDefaultFields($query){
    	
        $query->select(
            'network_elements.id',
            'network_elements.code',
            'network_elements.site_id',
            'network_elements.manufacturer_id',
            'network_elements.name',
            'network_elements.type_id',
            'network_elements.status',
            'network_elements.device_ip_address',
            'network_elements.software_version',
            'network_elements.foc_assignment_uplink1',
            'network_elements.foc_assignment_cid1',
            'network_elements.hon_assignment_uplink_port1',
            'network_elements.homing_node1',
            'network_elements.foc_assignment_uplink2',
            'network_elements.foc_assignment_cid2',
            'network_elements.hon_assignment_uplink_port2',
            'network_elements.homing_node2',
            'network_elements.date_decommissioned',
            'network_elements.new_node_name',
            'lib_manufacturers.name AS manufacturer_name',
            'lib_sub_domains.name AS type_name',
            'sites.name AS site_name',
            // 'dc_panel_items.breaker_no'
        )
        // ->leftJoin('dc_panel_items', function($join){
        //   $join->on('dc_panel_items.ne_id', '=', 'network_elements.id');
        // })
        ->leftJoin('lib_manufacturers', function($join){
            $join->on('lib_manufacturers.id', '=', 'network_elements.manufacturer_id');
        })
        ->leftJoin('lib_sub_domains', function($join){
            $join->on('lib_sub_domains.id', '=', 'network_elements.type_id');
        })
        ->leftJoin('sites', function($join){
            $join->on('sites.id', '=', 'network_elements.site_id');
        })
        ;
    }

    public function scopeWhereFields($query, $data){

        // if(Auth::user()->hasRole('User')){
        //     $query->where('employees.id', Auth::user()->id);
        // }
  
        if($data['ne_id']){
            $query->where('network_elements.id', $data['ne_id']);
        }
        
  
    }
}
