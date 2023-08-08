<?php

namespace App\Exports;
use DateTime;

use DB;
use App\User;
use App\Attendance;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AttendanceOtherSheets implements FromView,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    private $id;
    private $to;
    private $from;
    private $table_name;
   

    public function __construct($id,$from,$to,$table_name)
    {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    $this->table_name = $table_name;
    
   
    }

    public function view(): View
    {
        $users_all="";
        if($this->table_name=="obao")
        {
            $users_all=DB::table('users')
            ->join('obao_attendances','obao_attendances.employee_id','=','users.id')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('users.id','=',$this->id)
            ->whereDate('obao_attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('obao_attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
            ->orderBy('obao_attendances.time_in','ASC')
            ->get();

        }

        else
        {
            $users_all=DB::table('users')
            ->join('other_attendances','other_attendances.employee_id','=','users.id')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('users.id','=',$this->id)
            ->whereDate('other_attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
            ->whereDate('other_attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
            ->orderBy('other_attendances.time_in','ASC')
            ->get();

        }
        
   
        $fullname="No records found";

        if($this->id!="No records found")
        {

            $name = DB::table('employees')
            ->select('lastname','firstname','middlename')
            ->where('employee_id', $this->id)->first();
    
            $fullname= $name->lastname.", ".$name->firstname." ".$name->middlename;
        }

      

       
      

       
      


        return view('admin.attendancereport.exportother', [

        'users' => $users_all, //collection

        'fullname'=>$fullname,
        'start_date'=>$this->from,
        'end_date'=>$this->to,
        
        
        ]);
    }



    public function title(): string
    {

        if($this->id=="No records found")
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






    function explode_time($time) { //explode time and convert into seconds
        $time = explode(':', $time);
        $time = $time[0] * 3600 + $time[1] * 60;
        return $time;
    }

    function second_to_hhmm($time) { //convert seconds to hh:mm
        $hour = floor($time / 3600);
        $minute = strval(floor(($time % 3600) / 60));
        if ($minute == 0) {
            $minute = "00";
        } else {
            $minute = $minute;
        }
        $time = $hour . ":" . $minute.":00";
        return $time;
    }

    function timeDiff($hours_worked,$required_hours) { //get time difference
        $hours_worked = explode(':', $hours_worked);
        $required_hours = explode(':', $required_hours);
        $timediff="";
        if($hours_worked[1]=="00")
        {
           
            if($hours_worked[0]<$required_hours[0])
            {
                $required_hours[0]=$required_hours[0];
            }

            $hour_diff=$hours_worked[0]-$required_hours[0];

            $timediff=$hour_diff.':00:00';
        }
        else
        {
            //check if hours worked is greater than or equal to requied
            //
            $minute_diff=60-$hours_worked[1];

            if($hours_worked[0]<$required_hours[0])
            {
                $required_hours[0]=$required_hours[0]-1;
            }

            $hour_diff=$hours_worked[0]-$required_hours[0];
            $timediff=$hour_diff.':'.$minute_diff.':00';
            
        }
        
        
        
       

        return $timediff;
    }

    
}