<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genset extends Model
{
    /** @use HasFactory<\Database\Factories\GensetFactory> */
    use HasFactory;

    protected $table = "gensets";

    public function scopeDefaultFields($query){
    	
        $query->select(
            'gensets.id',
            'gensets.code',
            'gensets.site_id',
            'gensets.capacity',
            'gensets.rating',
            'gensets.type',
            'gensets.percent_utilization',
            'gensets.owner',
            'gensets.manufacturer_id',
            'gensets.brand',
            'gensets.model',
            'gensets.status',
            'gensets.date_manufactured',
            'gensets.date_installed',
            'gensets.date_accepted',
            'lib_manufacturers.name AS manufacturer_name',
        )
        ->leftJoin('lib_manufacturers', function($join){
            $join->on('lib_manufacturers.id', '=', 'gensets.manufacturer_id');
        })
        ;
    }
}
