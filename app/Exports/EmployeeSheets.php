<?php

namespace App\Exports;
use DB;
use App\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeeSheets implements FromView,WithTitle
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

    public function view(): View
    {
        $fullname="";
        $employees=DB::table('employees')
        ->join('attendances','attendances.employee_id','=','employees.employee_id')
        //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
        ->leftjoin('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
        ->select('attendances.time_in','attendances.accomplishment','attendances.status','work_settings.status as wstatus')
        ->where('employees.employee_id','=',$this->id)
        ->whereDate('attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
        ->whereDate('attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
        ->orderBy('attendances.time_in','ASC')
        ->get();

        /*
        $attendance = Attendance::where('employee_id', auth()->user()->id)
        ->orderBy('time_in', 'desc')
        //->get();     
        
                ->get();
        */

        $name = DB::table('employees')
        ->select('lastname','firstname','middlename')
        ->where('employee_id', $this->id)->first();


        if($this->id=="no results")
        {
            $fullname= 'No records found';
        }

        else
        {
            $fullname= $name->lastname.", ".$name->firstname." ".$name->middlename;  
        }
        

        return view('admin.employeereport.export', [    

        'users' => $employees,
        'start_date'=>$this->from,
        'end_date'=>$this->to,
        'name'=>$fullname,
        ]);
    }

    public function title(): string
    {

        if($this->id=="no results")
        {
            return 'No records found';
        }
        else
        {
            $name = DB::table('employees')
            ->select('lastname','firstname','middlename')
            ->where('employee_id', $this->id)->first();
    
            $fullname= $name->lastname.", ".$name->firstname." ".$name->middlename;

            return $fullname;
    
        }
        

        
    }


}
