<?php
  
  
namespace App\Exports;
use App\Models\NetworkElement;
use Maatwebsite\Excel\Concerns\FromCollection;
 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class NetworkElementExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array{
        return [
            'REGION',
            'SITE CODE',
            'SITE NAME',
            'NE CODE',
            'NE NAME',
            'NE TYPE',
            'STATUS',
            'DATE INSTALLED',
            'DATE DECOMMISSIONED',
        ];
    }

    public function query()
    {
        return $collection = NetworkElement::select(
            'organization.alias as area_name',
            'site.code AS site_code',
            'site.name AS site_name',
            'network_elements.code',
            'network_elements.name',
            'subdomain.name AS type_name',
            'network_elements.status',
            'network_elements.date_installed',
            'network_elements.date_decommissioned',
            
        )
        ->leftjoin('sites AS site','site.id','=','network_elements.site_id')
        ->leftjoin('lib_sub_domains AS subdomain','subdomain.id','=','network_elements.type_id')
        ->leftjoin('lib_organizations AS organization','organization.code','=','site.area')
        ;
    }
}

