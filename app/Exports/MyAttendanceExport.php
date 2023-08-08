<?php

namespace App\Exports;
use DB;
use App\User;
use App\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyAttendanceExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;
    private $to;
    private $from;

    public function __construct($id,$from,$to)
    {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    }

    public function query()
    {
//        return User::all();
        
            return Attendance::query()
            ->select('status','time_in','time_out','accomplishment')
            ->whereDate('time_in', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('time_in', '<=', date('Y-m-d',strtotime($this->to)))
            ->where('employee_id','=', $this->id)
            ->orderBy('time_in','asc');
       
        

    }

    public function headings(): array
    {
        return [
            'STATUS',
            'TIME IN',
            'TIME OUT',
            'ACCOMPLISHMENT',
        ];
    }
}
