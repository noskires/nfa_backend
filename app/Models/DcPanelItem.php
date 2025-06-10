<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DcPanelItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = "dc_panel_items";

    public function dcPanel(){
    	return $this->hasOne('App\Models\DcPanel', 'id', 'dc_panel_id');
    }
    
    public function scopeDefaultFields($query){
    	
        $query->select(
            'id',
            'dc_panel_id',
            'ne_id',
            'breaker_no',
            'current',
        )
        ;
    }
}
