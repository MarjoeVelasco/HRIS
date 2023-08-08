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

class AttendanceMonthlySheets implements FromView,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

   
    
    private $id;
    private $to;
    private $from;
    private $week;
    private $month_year;
    private $range;

    public function __construct($id,$from,$to,$week,$month_year,$range)
    {
    $this->id = $id;
    $this->from = $from;
    $this->to = $to;
    $this->week = $week;
    $this->month_year = $month_year;
    $this->range = $range;
    }

    public function view(): View
    {
        $collection = collect([]);

        $range_week = explode(",", $this->range);

        $accumulated_late_monthly="";
        $accumulated_overtime_monthly="";
        $accumulated_undertime_monthly="";
        $accumulated_worked_monthly="";

        $time_late_monthly=0;
        $time_undertime_monthly=0;
        $time_overtime_monthly=0;
        $time_worked_monthly=0;

        $days_absent_monthly=0;
        $days_holiday_monthly=0;
        $days_onleave_monthly=0;

        $required_working_hours_monthly=0;

        for($i=0;$i<count($range_week)-1;$i++)
        {
            
            $temp_range=explode("-", $range_week[$i]); 

            $from = date('Y-m-d', strtotime($this->month_year . "-" . $temp_range[0]));
            $to = date('Y-m-d', strtotime($this->month_year . "-" . $temp_range[1]));


            $users=DB::table('users')
            ->join('attendances','attendances.employee_id','=','users.id')
            ->join('employees','employees.employee_id','=','attendances.employee_id')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('users.id','=',$this->id)
            ->where('attendances.status','=','present')
            ->whereDate('attendances.time_in', '>=', $from)
            ->whereDate('attendances.time_in', '<=', $to)
            ->get();
            

            $required_working_hours_monthly=$required_working_hours_monthly+$users->count();

            
    
            $days_absent=DB::table('attendances')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('attendances.employee_id','=',$this->id)
            ->where('attendances.status','=','absent')
            ->whereDate('attendances.time_in', '>=', $from)
            ->whereDate('attendances.time_in', '<=', $to)
            ->count();

            $days_absent_monthly=$days_absent_monthly+$days_absent;
    
    
            $days_onleave=DB::table('attendances')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('attendances.employee_id','=',$this->id)
            ->where('attendances.status','=','on leave')
            ->whereDate('attendances.time_in', '>=', $from)
            ->whereDate('attendances.time_in', '<=', $to)
            ->count();
    
            $days_onleave_monthly=$days_onleave_monthly+$days_onleave;
           
            $time_late = 0;
            foreach ($users as $time_val) {

                
                    $time_late +=$this->explode_time($time_val->late); // this fucntion will convert all hh:mm to seconds
                    $time_late_monthly +=$this->explode_time($time_val->late); // this fucntion will convert all hh:mm to seconds
                
               
            }
    
            $time_undertime=0;
            foreach ($users as $time_val) {

                 
               
                    $temp_undertime = str_replace("-","",$time_val->undertime);
                    $time_undertime +=$this->explode_time($temp_undertime); // this fucntion will convert all hh:mm to seconds
                    $time_undertime_monthly +=$this->explode_time($temp_undertime); // this fucntion will convert all hh:mm to seconds
                
                
            }
    
            $time_overtime=0;
            foreach ($users as $time_val) {

                
                    $time_overtime +=$this->explode_time($time_val->overtime); // this fucntion will convert all hh:mm to seconds
                    $time_overtime_monthly +=$this->explode_time($time_val->overtime); // this fucntion will convert all hh:mm to seconds
                
               
            }
    
            $time_worked=0;
            foreach ($users as $time_val) {

               
                
                    $time_worked +=$this->explode_time($time_val->hours_worked); // this fucntion will convert all hh:mm to seconds
                    $time_worked_monthly +=$this->explode_time($time_val->hours_worked); // this fucntion will convert all hh:mm to seconds
                
               
            }
    
            $accumulated_late=$this->second_to_hhmm($time_late);
            $accumulated_undertime=$this->second_to_hhmm($time_undertime);
            $accumulated_overtime=$this->second_to_hhmm($time_overtime);
            $accumulated_worked=$this->second_to_hhmm($time_worked);
          
           
            $total_undertime=$this->getTotalUndertime($accumulated_undertime,$accumulated_overtime,);
            $total_temp_late_and_under=$this->getTotalLateUndertime($total_undertime,$accumulated_late);


            $ob=DB::table('users')
            ->join('attendances','attendances.employee_id','=','users.id')
            ->join('employees','employees.employee_id','=','attendances.employee_id')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('users.id','=',$this->id)
            ->where('attendances.status','=','OB')
            ->whereDate('attendances.time_in', '>=', $from)
            ->whereDate('attendances.time_in', '<=', $to)
            ->count();

            $ao=DB::table('users')
            ->join('attendances','attendances.employee_id','=','users.id')
            ->join('employees','employees.employee_id','=','attendances.employee_id')
            //->select('employees.employee_number','users.email','employees.lastname','employees.firstname','employees.middlename','employees.extname','employees.position','employees.item_number','employees.division','employees.unit','attendances.time_in','attendances.time_out','attendances.accomplishment')
            ->where('users.id','=',$this->id)
            ->where('attendances.status','=','AO')
            ->whereDate('attendances.time_in', '>=', $from)
            ->whereDate('attendances.time_in', '<=', $to)
            ->count();


            $item= (object) array(
            'late'=>$accumulated_late, 
            'overtime'=>$accumulated_overtime,
            'undertime'=>$accumulated_undertime, 
            'hours_worked'=>$accumulated_worked, 
            'days_absent'=>$days_absent+$days_onleave,
            'total_undertime'=>$total_undertime,
            'total_late_undertime'=>$total_temp_late_and_under,
            'expected_working_hours'=>$users->count(),
            'week_no' => $i+1,
            'total_OB' => $ob,
            'total_AO' => $ao,
            'from' =>$from,
            'to'=>$to,
            );

             $collection->push($item);
            
            }







        $users_all=DB::table('users')
        ->join('attendances','attendances.employee_id','=','users.id')
        ->select('attendances.time_in','attendances.time_out','attendances.accomplishment','attendances.status','attendances.time_status','attendances.late','attendances.undertime','attendances.overtime','attendances.hours_worked')
        ->where('users.id','=',$this->id)
        ->whereDate('attendances.time_in', '>=', date('Y-m-d',strtotime($this->from)))
        ->whereDate('attendances.time_in', '<=', date('Y-m-d',strtotime($this->to)))
        ->orderBy('attendances.time_in','ASC')
        ->get();


       // $user_data = collect([]);

        //if current collection == date 
        //continue
        //if not get last
        //then add item to collection

        //collection for date
        $date_collection= collect([]);
        $month_start = $this->from;
        $date_collection->push((object) array('date'=>$this->to,));  
        
        while($month_start!=$this->to)
        {               
            $date_collection->push((object) array('date'=>$month_start,));        
            $month_start = date('Y-m-d',strtotime('+1 day', strtotime($month_start)));    
        }

        //collection for date in query
        $temp_date_existing= collect([]);
        foreach($users_all as $user)
        {
            $temp_date_existing->push((object) array('date'=>date('Y-m-d',strtotime($user->time_in),)));  
        }   

       

        foreach($date_collection as $a)
        {
            $day = $a->date;
            $result = $temp_date_existing->where('date',$day); 

            if(count($result)==0)
            {
                $status="no_in_out";
                $day_checker = $this->isWeekend($day);
                if($day_checker)
                {
                $status="weekend";  
                }

                $item= (object) array(
                    'time_in'=>date('Y-m-d',strtotime($day)), 
                    'time_out'=>date('Y-m-d',strtotime($day)), 
                    'accomplishment'=>'no accomplishment', 
                    'status'=>$status,
                    'time_status'=>$status,
                    'late'=>'--',
                    'undertime'=>'--',
                    'overtime'=>'--',
                    'hours_worked'=>'--',
                    );
        
                    $users_all->push($item);
            }
        }
       

       
            $accumulated_late_monthly=$this->second_to_hhmm($time_late_monthly);
            $accumulated_undertime_monthly=$this->second_to_hhmm($time_undertime_monthly);
            $accumulated_overtime_monthly=$this->second_to_hhmm($time_overtime_monthly);
            $accumulated_worked_monthly=$this->second_to_hhmm($time_worked_monthly);



            $no_OB=$users_all->where('status','OB')->count();
            $no_AO=$users_all->where('status','AO')->count();

           // $sorted_user_attendance = $users_all->sortBy(['time_in','asc']);

           
            //collection for dates with halfday CTO
            $collection_cto = $users_all
            ->whereIn('time_status',['cto-afternoon','cto-morning']);
            
            //collection for 
            $half_day_cto= collect([]);

            
            foreach($collection_cto as $a)
            {
                
                $temp_present = $users_all
                ->where('status','present');

                        $time_in_temp=date('Y-m-d',strtotime($a->time_in));                      

                        foreach($temp_present as $b)
                        {
                            $time_in_present = date('Y-m-d',strtotime($b->time_in));

                            if($time_in_present==$time_in_temp)
                            {
                                $item= (object) array(
                                    'time_in'=>$b->time_in, 
                                    'time_out'=>$b->time_out, 
                                    'accomplishment'=>'no accomplishment', 
                                    'status'=>'CTO', 
                                    'time_status'=>$a->time_status,
                                    'late'=>$b->late, 
                                    'undertime'=>$b->undertime, 
                                    'overtime'=>$b->overtime, 
                                    'hours_worked'=>$b->hours_worked, 
                                    );

                                    $half_day_cto->push($item);
                            }

                    }              
            }

           
            $final_sorted_user_attendance = $users_all->sortBy(['time_in','asc']);
          
            $collection_cto_test = $users_all
            ->whereIn('time_status',['cto-afternoon','cto-morning']);

            $dates_removed= [];
            foreach($collection_cto_test as $a)
            {
                        $temp_present = $users_all
                        ->where('status','present');
                      
                        $time_in_temp=date('Y-m-d',strtotime($a->time_in));                       

                        foreach($temp_present as $b)
                        {
                            $time_in_present = date('Y-m-d',strtotime($b->time_in));

                            if($time_in_present==$time_in_temp)
                            {
                                array_push($dates_removed, $b->time_in, $a->time_in);
                            }
                        }              
            }

            //collection with removed dates
            $attendance_collection_removed_dates = $users_all->whereNotIn('time_in', $dates_removed);
            //merged collection with half day and removed dates
            $merged_attendance = $attendance_collection_removed_dates->merge($half_day_cto);
            //sorted collection
            $sorted_merged_attendance=$merged_attendance->sortBy(['time_in','asc']);

            //dd($sortedmergeddata);









        $fullname="No records found";
        $fullname_sign=" ";
        if($this->id!="No records found")
        {

            $name = DB::table('employees')
            ->select('lastname','firstname','middlename')
            ->where('employee_id', $this->id)->first();
    
            $fullname= $name->lastname.", ".$name->firstname." ".$name->middlename;
            $fullname_sign= $name->firstname." ".$name->middlename." ".$name->lastname;
        }


        $total_undertime_monthly=$this->getTotalUndertime($accumulated_undertime_monthly,$accumulated_overtime_monthly);
        $total_late_undertime_monthly=$this->getTotalLateUndertime($total_undertime_monthly,$accumulated_late_monthly);


       
       
        

        return view('admin.attendancereport.exportmonthly', [

            'users' => $sorted_merged_attendance,
    
            'fullname'=>$fullname,
            'fullname_sign'=>strtoupper($fullname_sign),
            'start_date'=>$this->from,
            'end_date'=>$this->to,
    
            'report_type'=>$this->week,
            'range'=>$this->range,
            'month_year'=>$this->month_year,
    
            'collection' => $collection,

            //for monthly
            'expected_working_hours'=>$required_working_hours_monthly,
            'late' => $accumulated_late_monthly,
            'overtime' => $accumulated_overtime_monthly,
            'undertime' => $accumulated_undertime_monthly, 
            'hours_worked' => $accumulated_worked_monthly, 
            'days_absent'=>$days_absent_monthly+$days_onleave_monthly,
            'total_undertime_monthly'=>$total_undertime_monthly,
            'total_late_undertime_monthly'=>$total_late_undertime_monthly,
            'total_OB' => $no_OB,
            'total_AO' => $no_AO,
            ]);

    }

    public function title(): string
        {
    
            if($this->id=="No records found")
            {
                return "No records found";
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



/***********  FUNCTIONS  ****************/       

    public function isWeekend($date)
    {
        //convert date to day name i.e Saturday
        $check_weekend = date('l', strtotime($date));
        //convert to lowercase
        $converted = strtolower($check_weekend);
        if ($converted == "saturday" || $converted == "sunday") {
            //day is a weekend
            return true;
        } else {
                return false;  
        }
    }



    function getTotalLateUndertime($under,$late)
    {
        $undertime = explode(':',$under);
        $latetime = explode(':',$late);

        $mins="";
        $hour="";

        $check_status=$undertime[0];

        if (str_contains($check_status, '-')) { 
            //add operation
            $temp_under=str_replace("-","",$undertime[0]);
            $temp_mins = $undertime[1] + $latetime[1];
            if($temp_mins>=60)
            {
                $mins=$temp_mins-60;
                $temp_hour=$temp_under+ $latetime[0]+1;
                $hour="-".$temp_hour;
            }

            else
            {
                $mins=$temp_mins;
                $temp_hour=$temp_under + $latetime[0];
                $hour="-".$temp_hour;
            }

            if($mins<10)
            {
            $TotalLateUndertime=$hour.":0".$mins.":00";
            }
            else
            {
            $TotalLateUndertime=$hour.":".$mins.":00";
            }
            
           

            

        }

        else
        {
            $TotalLateUndertime ="-".$late;


        }

        return $TotalLateUndertime;
       
    }



    function getTotalUndertime($under, $over)
    {
        $undertime = explode(':',$under);
        $overtime = explode(':',$over);
        $total_undertime="";

        $mins="";
        $hour="";
        
        if($undertime[0]>$overtime[0])
        {
            if($overtime[1]>$undertime[1])
                {
                    $mins = 60 - ($overtime[1] - $undertime[1]);
                    $temp_hour = $undertime[0] - 1; 
                    $hour = $overtime[0] - $temp_hour;
                    $hour='-'.$hour;
                }
                else if($overtime[1]<$undertime[1])
                {
                    $mins =$undertime[1] - $overtime[1];
                    $hour = $overtime[0] - $undertime[0];
                    $hour='-'.$hour;
    
                }

            else if($overtime[1]==$undertime[1])
            {
                $mins = $overtime[1] - $undertime[1];
                $hour = $overtime[0] - $undertime[0];
            }
        }

        else if($undertime[0]<$overtime[0])
        {
            if($overtime[1]>$undertime[1])
            {
                $mins = $overtime[1] - $undertime[1];
                $hour = $overtime[0] - $undertime[0];
            }
            else if($overtime[1]<$undertime[1])
            {
                $mins = 60 - ($undertime[1] - $overtime[1]);
                $temp_hour = $overtime[0] - 1; 
                $hour = $temp_hour - $undertime[0];

            }

            else if($overtime[1]==$undertime[1])
            {
                $mins = $overtime[1] - $undertime[1];
                $hour = $overtime[0] - $undertime[0];
            }

        }

        else if($undertime[0]==$overtime[0])
        {
            if($overtime[1]>$undertime[1])
            {
                $mins = $overtime[1] - $undertime[1];
                $hour = "0";
            }
            else if($overtime[1]<$undertime[1])
            {
                $mins = $undertime[1] - $overtime[1];
                $hour = "-0";

            }

            else if($overtime[1]==$undertime[1])
            {
                $mins = $undertime[1] - $overtime[1];
                $hour = "0";
            }

        }

        if($mins<10)
        {
            $total_undertime=$hour.":0".$mins.":00";
        }
        else
        {
            $total_undertime=$hour.":".$mins.":00";
        }
        

        return $total_undertime;


    }


    function getHour($time)
    {
        $time = explode(':', $time);
        $time = $time[0];
        return $time;
    }

    function getMinutes($time)
    {
        $time = explode(':', $time);
        $time = $time[1];
        return $time;
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

        if($minute<10&&$minute!='00')
        {
            $time = $hour . ":0" . $minute.":00";
        }
        else
        {
            $time = $hour . ":" . $minute.":00";
        }
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