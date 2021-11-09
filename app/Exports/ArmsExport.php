<?php

namespace App\Exports;

use App\Armsregister;
use App\Http\Controllers\ArmsregisterController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArmsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'type',
            'name',
            'mobile',
            'aadhar'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Armsregister::select('id', 'type', 'name', 'mobile', 'aadhar')->get()->toArray();
        // return Armsregister::get();
        // return collect(ArmsregisterController->index());
        return app('App\Http\Controllers\ArmsregisterController')->index1();
    }
}
