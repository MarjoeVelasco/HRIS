<?php

namespace App\Exports;
use DB;
use App\Errorlog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ErrorlogExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function query()
    {
//        return User::all();
       
            return Errorlog::query()
            ->select('message','file','line','url');

    }
}
