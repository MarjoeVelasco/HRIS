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

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CommunicationUtilitySheets implements FromView,WithTitle, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $id;
    private $month_year;
    private $to;
    private $from;


    public function __construct($id,$month_year,$from,$to)
    {
    $this->id = $id;
    $this->month_year = $month_year;
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

        //get wfh days
        $wfh_days=DB::table('employees')
        ->join('attendances','attendances.employee_id','=','employees.employee_id')
        ->leftjoin('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
        ->where('employees.employee_id','=',$this->id)
        ->where('work_settings.status','=','work from home')
        ->whereDate('attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
        ->whereDate('attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
        ->count();

        //get in office days
        $inoffice_days=DB::table('employees')
        ->join('attendances','attendances.employee_id','=','employees.employee_id')
        ->leftjoin('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
        ->where('employees.employee_id','=',$this->id)
        ->where('work_settings.status','=','in office')
        ->whereDate('attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
        ->whereDate('attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
        ->count();

        //collection for date
        $date_collection= collect([]);
        $month_start = $this->from;
        $date_collection->push((object) array('date'=>$this->to,));  

        //iterate to include all dates in specified month
        while($month_start!=$this->to)
        {               
            $date_collection->push((object) array('date'=>$month_start,));        
            $month_start = date('Y-m-d',strtotime('+1 day', strtotime($month_start)));    
        }

        //collection for date in query
        $temp_date_existing= collect([]);
        foreach($employees as $user)
        {
            $temp_date_existing->push((object) array('date'=>date('Y-m-d',strtotime($user->time_in),)));  
        }

        //add missing dates to collection
        foreach($date_collection as $a)
        {
            $day = $a->date;
            $result = $temp_date_existing->where('date',$day); 

            if(count($result)==0)
            {
                
                $item= (object) array(
                    'time_in'=>$day, 
                    'accomplishment'=>'', 
                    'status'=>'',
                    'wstatus'=>'',
                    );
        
                    $employees->push($item);
            }

        }


        $final_sorted_user_attendance = $employees->sortBy(['time_in','asc']);

       
        $name = DB::table('employees')
        ->select('lastname','firstname','middlename','position','division')
        ->where('employee_id', $this->id)->first();

        $temp = explode("(", $name->division);
        $division_short = str_replace(")", "", $temp[1]);


        if($this->id=="no results")
        {
            $fullname= 'No records found';
        }

        else
        {
            if($name->middlename!="")
            {
                $fullname= $name->lastname.", ".$name->firstname." ".substr($name->middlename, 0, 1).".";  
            }
            else
            {
                $fullname= $name->lastname.", ".$name->firstname." ".substr($name->middlename, 0, 1);  
            }

        }
        

        return view('admin.utilityreport.export', [    
        
        'fullname' => $fullname,
        'users' => $final_sorted_user_attendance,
        'wfh_days' => $wfh_days,
        'inoffice_days' => $inoffice_days,
        'month_year' => $this->month_year,
        'position' => $name->position,
        'firstname' => strtoupper($name->firstname),
        'lastname' => strtoupper($name->lastname),
        'middlename' => strtoupper(substr($name->middlename, 0, 1)),
        'division' => $division_short,
        'to' => $this->to,
        'from' => $this->from
        /*
        
        'start_date'=>$this->from,
        'end_date'=>$this->to,
        'name'=>$fullname,

        */
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:J100'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
            }
        ];
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
