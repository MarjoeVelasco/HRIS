<?php

namespace App\Exports;
use DB;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveExport implements FromQuery, WithHeadings
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
        if($this->id=="All")
        {

            return User::query()
            ->join('employees','employees.employee_id','=','users.id')
            ->join('filed_leaves','filed_leaves.employee_id','=','employees.employee_id')
            ->select('employees.lastname','employees.firstname','users.email','filed_leaves.leave_type','filed_leaves.status','filed_leaves.inclusive_dates','filed_leaves.no_days','filed_leaves.created_at')
            ->where('filed_leaves.status','!=','Cancelled')
            ->whereDate('filed_leaves.created_at', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('filed_leaves.created_at', '<=', date('Y-m-d',strtotime($this->to)));
        }
        else
        {
            return User::query()
            ->join('employees','employees.employee_id','=','users.id')
            ->join('filed_leaves','filed_leaves.employee_id','=','employees.employee_id')
            ->select('employees.lastname','employees.firstname','users.email','filed_leaves.leave_type','filed_leaves.status','filed_leaves.inclusive_dates','filed_leaves.no_days','filed_leaves.created_at')
            ->where('filed_leaves.status','!=','Cancelled')
            ->whereDate('filed_leaves.created_at', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('filed_leaves.created_at', '<=', date('Y-m-d',strtotime($this->to)))
            ->where('users.id','=', $this->id);
        }
        

    }

    public function headings(): array
    {
        return [
            'Lastname',
            'Firstname',
            'Email',
            'Leave Type',
            'Status',
            'Date Availment',
            'No Days',
            'Requested'
        ];
    }
}
