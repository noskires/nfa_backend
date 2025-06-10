<?php
  
  
namespace App\Exports;
use App\Models\Site;
use Maatwebsite\Excel\Concerns\FromCollection;
 
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use DB;

class SiteExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function headings(): array{
        return [
            'ID',
            'Code',
            'Name',
            'Area',
            'Status',
            'Category',
        ];
    }

    public function query()
    {
        return $collection = Site::select(
            'sites.id',
            'sites.code',
            'sites.name',
            'sites.area',
            'sites.status',
            'sites.site_category_id',
        );
    }
}

