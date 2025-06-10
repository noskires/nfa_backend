<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acu extends Model
{
    use HasFactory;

    protected $table = "acus";

    public function scopeDefaultFields($query){
    	
        $query->select(
            'acus.id',
            'acus.code',
            'acus.site_id',
            'acus.capacity',
            'acus.type',
            'acus.installation_type',
            'acus.operation_type',
            'acus.manufacturer_id',
            'acus.brand',
            'acus.model',
            'acus.date_installed',
            'acus.date_accepted',
            'lib_manufacturers.name AS manufacturer_name',
        )
        ->leftJoin('lib_manufacturers', function($join){
            $join->on('lib_manufacturers.id', '=', 'acus.manufacturer_id');
        })
        ;
    }

}
