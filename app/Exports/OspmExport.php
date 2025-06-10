<?php
  
  
namespace App\Exports;
use App\Models\Ospm;
use Maatwebsite\Excel\Concerns\FromCollection;
 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class OspmExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array{
        return [
            'Work Order',
            'Status',
            'Severity',
            'Company',
            'Region',
            'Division',
            'Section',
            'Contractor',
            'Sub Contractor',
            'Endorsed Date',
            'Service Restored Date',
            'Full Link Restored Date',
        ];
    }

    public function query()
    {
        return $collection = Ospm::select(
            'ospm.work_order_no',
            'ospm.status',
            'ospm.severity',
            'ospm.company',
            'ospm.region',
            'ospm.division',
            'ospm.section',
            'ospm.contractor',
            'ospm.sub_contractor',
            'ospm.endorsed_at',
            'ospm.service_restored_at',
            'ospm.full_link_restored_at',
        );
    }
}

