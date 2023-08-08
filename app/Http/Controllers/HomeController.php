<?php
namespace App\Http\Controllers;
use App\Attendance;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\User;
use App\ErrorLog;
use App\Forms;
use App\Voters;
use App\Holidays;
use App\Other_attendances;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $vote = false;
        $active_form = Forms::select('id')->where('status',1)->first();
        if($active_form!=null) {
           if(Voters::where('user_id',auth()->user()->id)->where('form_id',$active_form->id)->exists()) {
                $vote = true;
           }
        }






        //abort(404, "The Partner was not found");

        //LOGIC FOR AUTO ABSENT 3.0
        //get current date
        //yesterday = current date -1 day
        //get last time out
        //if current date is weekend or holiday do nothing
        //if current date is a working day
        //loop to last time out
        //do nothing if holiday
        //get current day
        $currentDate = date('Y-m-d');
        //check if current day is a working day
        $working_day_flag = $this->isWorkingDay($currentDate,auth()->user()->id);
        //if current day is a working day
        if ($working_day_flag) {
            //initiate auto time out process
            //check if user count record >=1
            $employee_record = DB::table('attendances')
                ->where('employee_id', auth()->user()->id)
                ->get();
            //if yes
            if ($employee_record->isNotEmpty()) {
                //current day minus 1 day
                $yesterday = date(
                    'Y-m-d',
                    strtotime('-1 day', strtotime($currentDate))
                );
                //if yes get last record.
                //orderby time in date desc
                //get all records where is = user id
                //get first()
                $employee_record_last = DB::table('attendances')
                    ->select('attendance_id', 'time_in')
                    ->where('employee_id', auth()->user()->id)
                    ->where('status', 'present')
                    ->orderBy('time_in', 'DESC')
                    ->first();
                //convert datetime to just date
                $last_date_record = date(
                    'Y-m-d',
                    strtotime($employee_record_last->time_in)
                );
                if ($last_date_record < $currentDate) {
                    while ($yesterday > $last_date_record) {
                        //check if yesterday is a holiday or weekened
                        $isWorking = $this->isWorkingDay($yesterday,auth()->user()->id);
                        if (!$isWorking) {
                            //if holiday or weekend
                            //minus 1 day then loop ulit
                            $yesterday = date(
                                'Y-m-d',
                                strtotime('-1 day', strtotime($yesterday))
                            );
                        } else {
                            //if not holiday
                            $record_check = DB::table('attendances')
                                ->select('time_in')
                                ->where('employee_id', auth()->user()->id)
                                ->whereDate('time_in', $yesterday)
                                ->get();
                            if ($record_check->isEmpty()) {
                                //if no record
                                //mark as absent then minus 1 day then loop ulit
                                Attendance::create([
                                    'employee_id' => auth()->user()->id,
                                    'status' => 'absent',
                                    'time_status' => 'absent',
                                    'time_in' => $yesterday,
                                    'time_out' => $yesterday,
                                    'undertime' => '00:00:00',
                                    'overtime' => '00:00:00',
                                    'hours_worked' => '00:00:00',
                                    'late' => '00:00:00',
                                ]);
                                $yesterday = date(
                                    'Y-m-d',
                                    strtotime('-1 day', strtotime($yesterday))
                                );
                            } else {
                                //if has record minus 1 day then loop ulit
                                $yesterday = date(
                                    'Y-m-d',
                                    strtotime('-1 day', strtotime($yesterday))
                                );
                            }
                        }
                    }
                }
            }
        }
        /*
        
        //LOGIC FOR AUTO ABSENT
        
        //true means working day
        //false means nonworking day (holiday or weekend)
        
        //get current day
        $currentDate = date('Y-m-d');
        //check if current day is a working day
        $working_day_flag=$this->isWorkingDay($currentDate);
        //if working day proceed time out else do nothing
        if($working_day_flag)
        {
        //current day minus 1 day
        $yesterday = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($currentDate))));
        //check if weekends or holiday yung yesterday
        $working_day_flag_yesterday=$this->isWorkingDay($yesterday);
        
        //while yesterday is not a working day
        //loop until day is a working day
        while(!$working_day_flag_yesterday)
        {               
            //minus 1 day to yesterday
            $temp=$yesterday;
            $yesterday = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($temp))));
            //check if yesterday is a working day
            $working_day_flag_yesterday=$this->isWorkingDay($yesterday);
            //stop if working day flag yesterday is true
        }
        
        //get employees without time in
        $emp_id=DB::table('users')->select('id')
        ->whereNotExists( function ($query) use ($yesterday){
            $query->select(DB::raw(1))
            ->from('attendances')
            ->whereRaw('users.id = attendances.employee_id')
            ->whereDate('attendances.time_in', date('Y-m-d',(strtotime ($yesterday))));
        })
        ->get();
        
        foreach ($emp_id as $user) {
         
        //time out all employees with no record yesterday
            Attendance::create([
            'employee_id'=>$user->id,
            'status' => 'absent', 
            'time_status' => 'absent', 
            'time_in' => $yesterday,
            'time_out' => $yesterday,
            'undertime' =>'00:00:00',
            'overtime' =>'00:00:00',
            'hours_worked' =>'00:00:00',
            'late' =>'00:00:00',    
            ]);
        
        }
        
        }
        
        */
        /*
        
        DB::beginTransaction();
            try
            {
        //first get current date
        $currentDate = date('Y-m-d');
        //check if monday
        $checkMonday=$this->isMonday($currentDate);
        $yesterday="";
        if($checkMonday==1)
        {   
            //if monday substract 3 days to check friday
            $yesterday = date('Y-m-d',(strtotime ( '-3 days' , strtotime ($currentDate))));
        }
        else
        {
            $yesterday = date('Y-m-d',(strtotime ( '-1 day' , strtotime ($currentDate))));
        }
        
        
        
        
        
        //check if day is weekdays
        $check_weekend = $this->isWeekend($yesterday);
        $latestRecordDate1=$check_weekend;
        //if day is not weekend
        if($check_weekend==0)
        {
            //get attendance record yesterday
            $rec = DB::table('attendances')->whereDate('time_in', $yesterday);
            //check if it has records
            if ($rec)
            {
               
              
                //get all employees with no time in yesterday
                $emp_id=DB::table('users')->select('id')
                ->whereNotExists( function ($query){
                    $query->select(DB::raw(1))
                    ->from('attendances')
                    ->whereRaw('users.id = attendances.employee_id')
                    ->whereDate('attendances.time_in', date('Y-m-d',(strtotime ( '-1 day' , strtotime (date('Y-m-d'))))));
                })
                ->get();
                
                foreach ($emp_id as $user) {
                 
                //time out all employees with no record yesterday
                    Attendance::create([
                    'employee_id'=>$user->id,
                    'status' => 'absent', 
                    'time_status' => 'absent', 
                    'time_in' => $yesterday,
                    'time_out' => $yesterday,
                    'undertime' =>'00:00:00',
                    'overtime' =>'00:00:00',
                    'hours_worked' =>'00:00:00',
                    'late' =>'00:00:00', 
                    ]);
                
                }
            }
        }
        
                DB::commit();
            }
            catch(\Exception $e)
            {
                //rollback all transactions made to database
                DB::rollback();
                $log = new ErrorLog;
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();;
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                //redirect back to home with error
                return back()
                    ->with('found', 'Execution Error. Record Not Saved! Please contact the administrator');
            }   
        */


        $statusMark = true;
        $isHoliday=$this->isWorkingDay($currentDate,auth()->user()->id);
        if(!$isHoliday) //false //is a holiday
        {

            $obaoFlag=$this->isObao($currentDate,auth()->user()->id);
            $table_name="";
            $attendance_type="";

            if($obaoFlag)
            {
                $table_name="obao_attendances";
                $attendance_type="(OB/AO)";
            }

            else
            {
                $table_name="other_attendances";
                $attendance_type="(Holidays and Weekends)";
               
            }


            
            $attendance = DB::table($table_name)
            ->where('employee_id',auth()->user()->id)
            ->get();
            $users = Employee::where('employee_id', '=', auth()->user()->id)->get();
            //$name = $users->firstname." ".$users->middlename." ".$users->lastname." ".$users->extname;
            //$position = $users->position;
            foreach ($attendance as $at) {
                if (
                    $currentDate == date('Y-m-d', strtotime($at->created_at)) &&
                    $at->time_status == "timed_out"
                ) {
                    $statusMark = false;
                } elseif (
                    $currentDate == date('Y-m-d', strtotime($at->created_at)) &&
                    $at->time_status == "timed_in"
                ) {
                    $statusMark = false;
                }
            }
            $rec = DB::table($table_name)
                ->where('employee_id', auth()->user()->id)
                ->where('status','present')
                ->whereDate('time_in', $currentDate)
                ->first();
            
            $work_envi="";

            if($rec)
            {
                $work_envi=DB::table('work_settings')
                ->where('attendance_id', $rec->attendance_id)
                ->first();
            }
            

            if (!empty($rec->time_in) && empty($rec->time_out)) {
                return view('users.index')
                    ->with('users', $users)
                    //->with('latest_date', $emp_id)
                    //->with('name',$name)
                    //->with('position',$position)
                    ->with('hours_worked','No Entry')
                    ->with('late','Not Applicable')
                    ->with('statusMark', false)
                    ->with('work_envi', 'work from home')
                    ->with('time_in', $rec->time_in)
                    ->with('vote', $vote)
                    ->with('time_out', 'No Entry');
            } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                return view('users.index')
                    ->with('users', $users)
                    // ->with('position',$position)
                    //->with('latest_date', $emp_id)
                    ->with('hours_worked',$rec->hours_worked)
                    ->with('late','Not Applicable')
                    ->with('statusMark', false)
                    ->with('work_envi', 'work from home')
                    ->with('time_in', $rec->time_in)
                    ->with('vote', $vote)
                    ->with('time_out', $rec->time_out);
            } else {
                return view('users.index')
                    ->with('users', $users)
                    //->with('position',$position)
                    //->with('latest_date', $emp_id)
                    ->with('hours_worked','No Entry')
                    ->with('late','Not Applicable')
                    ->with('statusMark', true)
                    ->with('work_envi', 'work from home')
                    ->with('time_in', 'No Entry')
                    ->with('vote', $vote)
                    ->with('time_out', 'No Entry');
            }
        }

        else
        {
            $attendance = Attendance::where(
                'employee_id',
                auth()->user()->id
            )->get();
            $users = Employee::where('employee_id', '=', auth()->user()->id)->get();
            //$name = $users->firstname." ".$users->middlename." ".$users->lastname." ".$users->extname;
            //$position = $users->position;
            foreach ($attendance as $at) {
                if (
                    $currentDate == date('Y-m-d', strtotime($at->created_at)) &&
                    $at->time_status == "timed_out"
                ) {
                    $statusMark = false;
                } elseif (
                    $currentDate == date('Y-m-d', strtotime($at->created_at)) &&
                    $at->time_status == "timed_in"
                ) {
                    $statusMark = false;
                }
            }
            $rec = DB::table('attendances')
                ->where('employee_id', auth()->user()->id)
                ->whereDate('time_in', $currentDate)
                ->where('status','present')
                ->orderBy('attendance_id','desc')
                ->first();
            
            $work_envi="";
            if($rec)
                {
                    $work_envi=DB::table('work_settings')
                    ->where('attendance_id', $rec->attendance_id)
                    ->first();
                }
            
            if (!empty($rec->time_in) && empty($rec->time_out)) {
                return view('users.index')
                    ->with('users', $users)
                    //->with('latest_date', $emp_id)
                    //->with('name',$name)
                    //->with('position',$position)
                    ->with('hours_worked',$rec->hours_worked)
                    ->with('late',$rec->late)
                    ->with('statusMark', false)
                    ->with('work_envi', $work_envi->status)
                    ->with('time_in', $rec->time_in)
                    ->with('vote', $vote)
                    ->with('time_out', 'No Entry');
            } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                return view('users.index')
                    ->with('users', $users)
                    // ->with('position',$position)
                    //->with('latest_date', $emp_id)
                    ->with('hours_worked',$rec->hours_worked)
                    ->with('late',$rec->late)
                    ->with('statusMark', false)
                    ->with('work_envi', $work_envi->status)
                    ->with('time_in', $rec->time_in)
                    ->with('vote', $vote)
                    ->with('time_out', $rec->time_out);
            } else {
                return view('users.index')
                    ->with('users', $users)
                    //->with('position',$position)
                    //->with('latest_date', $emp_id)
                    ->with('hours_worked','No Entry')
                    ->with('late','No Entry')
                    ->with('statusMark', true)
                    ->with('work_envi', 'work from home')
                    ->with('time_in', 'No Entry')
                    ->with('vote', $vote)
                    ->with('time_out', 'No Entry');
            }
        }
    }
       





    
    public function isWorkingDay($date,$id)
    {
        //convert date to day name i.e Saturday
        $check_weekend = date('l', strtotime($date));
        //convert to lowercase
        $converted = strtolower($check_weekend);
        if ($converted == "saturday" || $converted == "sunday") {
            //day is a weekend
            return false;
        } else {
            $holidays = DB::table('holidays')
                ->select('id')
                ->where('inclusive_dates', 'like', '%' . $date . '%')
                ->get();
            //check if holiday
            if ($holidays->isNotEmpty()) {
                //day is holiday
                return false;
            } 
            
            else {

                $obao = DB::table('obaos')
                ->select('id')
                ->where('employee_id',$id)
                ->where('inclusive_dates', $date)
                ->get();

                if ($obao->isNotEmpty()) {
                    //day is holiday
                    return false;
                } else {
                    return true;
                }

            }
        }
    }

    public function isObao($date,$id)
    {
        //convert date to day name i.e Saturday
       
        $obao = DB::table('obaos')
                ->select('id')
                ->where('employee_id',$id)
                ->where('inclusive_dates', 'like', '%' . $date . '%')
                ->get();
            //check if holiday
            if ($obao->isNotEmpty()) {
                //day is holiday
                return true;
            } else {
                return false;
            }
        
    }

}
