<?php

namespace App\Exports;
use DB;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveArchiveExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;
    private $to;
    private $from;

    public function __construct($id,$from,$to){
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    }

    public function query() {
        if($this->id=="All") {
            return User::query()
            ->join('employees','employees.employee_id','=','users.id')
            ->join('leaves','leaves.employee_id','=','employees.employee_id')
            ->select('employees.lastname','employees.firstname','users.email','leaves.leave_type','leaves.details','leaves.status','leaves.inclusive_dates','leaves.no_days','leaves.created_at')
            ->whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($this->to)));
        }
        else {
            return User::query()
            ->join('employees','employees.employee_id','=','users.id')
            ->join('leaves','leaves.employee_id','=','employees.employee_id')
            ->select('employees.lastname','employees.firstname','users.email','leaves.leave_type','leaves.details','leaves.status','leaves.inclusive_dates','leaves.no_days','leaves.created_at')
            ->whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($this->to)))
            ->where('users.id','=', $this->id);
        }
        

    }

    public function headings(): array {
        return [
            'Lastname',
            'Firstname',
            'Email',
            'Leave Type',
            'Details',
            'Status',
            'Date Availment',
            'No Days',
            'Requested'
        ];
    }
}
