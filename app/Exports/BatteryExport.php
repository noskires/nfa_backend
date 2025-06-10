<?php
  
  
namespace App\Exports;
use App\Models\Battery;
use Maatwebsite\Excel\Concerns\FromCollection;
 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class BatteryExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array{
        return [
            'REGION',
            'SITE CODE',
            'SITE NAME',
            'TYPE',
            'CAPACITY',
            'BANK',
            'STATUS',
            'DATE INSTALLED',
            // 'DATE DECOMMISSIONED',

        ];
    }

    public function query()
    {
        return $collection = Battery::select(
            'organization.alias as area_name',
            'site.code AS site_code',
            'site.name AS site_name',
            'batteries.type',
            'batteries.capacity',
            'batteries.bank',
            'batteries.status',
            'batteries.date_installed',
            // 'batteries.date_decommissioned',
            
        )
        ->leftjoin('sites AS site','site.id','=','batteries.site_id')
        // ->leftjoin('lib_sub_domains AS subdomain','subdomain.id','=','batteries.type_id')
        ->leftjoin('lib_organizations AS organization','organization.code','=','site.area')
        ;
    }
}

