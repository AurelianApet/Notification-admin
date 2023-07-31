<?php 
namespace App\Exports;

use App\BillRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class BillRecordExportor implements FromCollection
{
    use Exportable;

    public function collection()
    {
        return BillRecord::all();
    }
}