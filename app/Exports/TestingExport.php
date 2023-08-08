<?php

namespace App\Exports;


use DB;
use App\User;
use App\Attendance;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TestingExport implements FromView
{

    private $id;
    private $to;
    private $from;

    public function __construct($id,$from,$to)
    {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    }

    
    public function view(): View
    {
        return view('admin.export', [
        $users = DB::table('users')
        ->join('attendances','attendances.employee_id','=','users.id')
        ->join('employees','employees.employee_id','=','attendances.employee_id')
        //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
        ->whereDate('attendances.created_at', '>=', date('Y-m-d',strtotime($this->from)))
        ->whereDate('attendances.created_at', '<=', date('Y-m-d',strtotime($this->to)))
        ]);
    }
}

?>
