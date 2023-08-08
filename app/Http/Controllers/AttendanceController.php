<?php
namespace App\Http\Controllers;
use App\Attendance;
use App\WorkSetting;
use App\Other_attendances;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\ErrorLog;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\HybridEmployee;


class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentDate = date('Y-m-d');
        $statusMark = true;

        $workingDayFlag = $this->isWorkingDay($currentDate,auth()->user()->id);

        if($workingDayFlag)
        {
            $attendance = Attendance::where(
                'employee_id',
                auth()->user()->id
            )->get();
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
                ->where('status','present')
                ->whereDate('time_in', $currentDate)
                ->first();
            if (!empty($rec->time_in) && empty($rec->time_out)) {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', $rec->time_in)
                    ->with('time_out', 'No Entry');
            } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', $rec->time_in)
                    ->with('time_out', $rec->time_out);
            } else {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', 'No Entry')
                    ->with('time_out', 'No Entry');
            }
        }

        else
        {



            $attendance = Other_attendances::where(
                'employee_id',
                auth()->user()->id
            )->get();
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
            $rec = DB::table('other_attendances')
                ->where('employee_id', auth()->user()->id)
                ->where('status','present')
                ->whereDate('time_in', $currentDate)
                ->first();
            if (!empty($rec->time_in) && empty($rec->time_out)) {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', $rec->time_in)
                    ->with('time_out', 'No Entry');
            } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', $rec->time_in)
                    ->with('time_out', $rec->time_out);
            } else {
                return view('users.index')
                    ->with('statusMark', $statusMark)
                    ->with('time_in', 'No Entry')
                    ->with('time_out', 'No Entry');
            }
        }

        
        /*
            if(!empty($at->time_in)){
                return view('users.index')->with('statusMark', $statusMark)->with('time_in',$at->time_in)->with('time_out','No Entry');
            }    
            else if(!empty($at->time_out) && !empty($at->time_in) ){
                return view('users.index')->with('statusMark', $statusMark)->with('time_in',$at->time_in)->with('time_out',$at->time_out);
            }
            else{
                return view('users.index')->with('statusMark', $statusMark)->with('time_in','No Entry')->with('time_out','No Entry');
            }
        */
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //get system settings for time in
        $time_in_settings = AttendanceSetting::select('system_setting_value')
                    ->where('system_setting_name','time in')
                    ->first();
         
        //if onsite, check if work place is work from home 
        if($time_in_settings->system_setting_value == 'onsite') {
            //if wfh is checked, check if user is allowed
            if($request->input('work_place')=="work from home") {
                //check in database, if false, return wfh is not allowed
                $hybrid = HybridEmployee::select('id')->where('user_id',auth()->user()->id)->first();
                if(!$hybrid) {
                    return back()->with('found','Work from home is no longer allowed');
                } 
            }
        }

        $canMark = true;
        $currentDate = date('Y-m-d');
        $statusMark = true;

        $workingDayFlag = $this->isWorkingDay($currentDate,auth()->user()->id);

        //if day is a working day (not ob/ao, weekend, holiday)
        if($workingDayFlag)
        {
            $attendance = Attendance::where(
                'employee_id',
                auth()->user()->id
            )->get();
            foreach ($attendance as $at) {
                if (
                    $currentDate == date('Y-m-d', strtotime($at->time_in)) &&
                    $at->time_status == "timed_out"
                ) {
                    $canMark = false;
                    return back()
                        ->with('timeout', $at->attendance_id)
                        ->with('double_timeout', true)
                        ->with('statusMark', true)
                        ->with('time_in', $at->time_in)
                        ->with('time_out', $at->time_out);
                    break;
                }
                if (
                    $currentDate == date('Y-m-d', strtotime($at->time_in)) &&
                    $at->time_status == "timed_in"
                ) {
                    $canMark = false;
                    return back()
                        ->with('timeout', $at->attendance_id)
                        ->with('double_timeout', false)
                        ->with('statusMark', false)
                        ->with('time_in', $at->time_in)
                        ->with('time_out', $at->time_out);
                    break;
                }
            }
            if ($canMark) {
                if (date("H:i:s", strtotime($request->input('time_in')))>=date("H:i:s", strtotime("18:30:00"))) {
                    return back()->with('found','Too late to time in, Please note that the last time out is 6:30 pm.');
                }

                $time_in_final = $this->checkshiftType($request->get('employee_id'),date("H:i:s", strtotime($request->input('time_in'))));

                //check if has cto na half day
                $hasCTO=$this->hasCTO($currentDate,auth()->user()->id);
                
                $check_late="";
                //if morning cto nya, use checking of late in the afternoon function
                if (strpos($hasCTO, 'morning') !== false) {
                    $check_late=$this->checkLateAfternoon(date("H:i:s", strtotime($request->input('time_in'))));   
                }

                //if afternoon cto nya, use ordinary late checker
                else {
                    $check_late=$this->checkLate(date("H:i:s", strtotime($request->input('time_in'))));

                }
                

                $attendance_data=Attendance::create([
                                'status' => $request->input('status'),
                                'time_status' => $request->input('time_status'),
                                'time_in' =>date("Y-m-d", strtotime($request->input('time_in')))." ".$time_in_final,
                                'employee_id' => auth()->user()->id,
                                'late' => $check_late,
                                ]);
                
                WorkSetting::create([
                            'attendance_id' => $attendance_data->attendance_id,
                            'status' => $request->input('work_place'),
                            ]);
                

                $show_in = date("Y-m-d", strtotime($request->input('time_in'))) ." " .$time_in_final;
                
                $rec = DB::table('attendances')
                    ->where('employee_id', auth()->user()->id)
                    ->first();
                
              

                if (!empty($rec->time_in) && empty($rec->time_out)) {
                    return back()
                        ->with('success', 'TIME IN SUCCESSFUL: ' . date('F d, Y h:i a', strtotime($show_in)))
                        ->with('statusMark', $statusMark)
                        ->with('time_in', $rec->time_in)
                        ->with('time_out', 'No Entry');
                } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                    return back()
                        ->with('success', 'TIME IN SUCCESSFUL: ' . date('F d, Y h:i a', strtotime($show_in)))
                        ->with('statusMark', $statusMark)
                        ->with('time_in', $rec->time_in)
                        ->with('time_out', $rec->time_out);
                } else {
                    return back()
                        ->with(
                            'success',
                            'TIME IN FAILED. PLS. TRY AGAIN: ' . date('F d, Y h:i a', strtotime($show_in))
                        )
                        ->with('statusMark', $statusMark)
                        ->with('time_in', 'No Entry')
                        ->with('time_out', 'No Entry');
                }
            }
        }

        //if holiday or weekend or obao
        else
        {
            //check if current on ob/ao
            $obaoFlag=$this->isObao($currentDate,auth()->user()->id);
            $table_name="";
            $attendance_type="";

            //if ob/ao
            if($obaoFlag) {
                $table_name="obao_attendances";
                $attendance_type="(OB/AO)";
            }

            //if holiday or weekend
            else {
                $table_name="other_attendances";
                $attendance_type="(Holidays and Weekends)";
            }

            $attendance = DB::table($table_name)->where('employee_id',auth()->user()->id)->get();

            foreach ($attendance as $at) {
                if ($currentDate == date('Y-m-d', strtotime($at->time_in)) && $at->time_status == "timed_out") {

                    $canMark = false;
                    return back()
                        ->with('timeout', $at->attendance_id)
                        ->with('double_timeout', true)
                        ->with('statusMark', true)
                        ->with('time_in', $at->time_in)
                        ->with('time_out', $at->time_out);
                    break;
                }

                if ($currentDate == date('Y-m-d', strtotime($at->time_in)) &&$at->time_status == "timed_in") {
                    $canMark = false;
                    return back()
                        ->with('timeout', $at->attendance_id)
                        ->with('double_timeout', false)
                        ->with('statusMark', false)
                        ->with('time_in', $at->time_in)
                        ->with('time_out', $at->time_out);
                    break;
                }
            }
            if ($canMark) {
                $time_in_final = $this->checkshiftType($request->get('employee_id'),date("H:i:s", strtotime($request->input('time_in'))));
                
                $employee = DB::table($table_name)->insert([
                    'status' => $request->input('status'),
                    'time_status' => $request->input('time_status'),
                    'time_in' => date("Y-m-d", strtotime($request->input('time_in'))). " " .$time_in_final,
                    'employee_id' => auth()->user()->id,
                ]);

                $show_in = date("Y-m-d", strtotime($request->input('time_in')))." ".$time_in_final;

                $rec = DB::table($table_name)
                    ->where('employee_id', auth()->user()->id)
                    ->first();
                if (!empty($rec->time_in) && empty($rec->time_out)) {
                    return back()
                        ->with('success', 'TIME IN SUCCESSFUL: ' . $show_in)
                        ->with('statusMark', $statusMark)
                        ->with('time_in', $rec->time_in)
                        ->with('time_out', 'No Entry');
                } elseif (!empty($rec->time_out) && !empty($rec->time_in)) {
                    return back()
                        ->with('success', 'TIME IN SUCCESSFUL: ' . $show_in)
                        ->with('statusMark', $statusMark)
                        ->with('time_in', $rec->time_in)
                        ->with('time_out', $rec->time_out);
                } else {
                    return back()
                        ->with('success','TIME IN FAILED. PLS. TRY AGAIN: ' . $show_in)
                        ->with('statusMark', $statusMark)
                        ->with('time_in', 'No Entry')
                        ->with('time_out', 'No Entry');
                }
            }
        }
        return back()->with('statusMark', true);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function accomplishment(Request $request)
    {



        $currentDate = date('Y-m-d');
        $statusMark = true;

        $workingDayFlag = $this->isWorkingDay($currentDate,auth()->user()->id);

        //if working day
        if($workingDayFlag) {
            $rec = DB::table('attendances')->where('employee_id', auth()->user()->id)->first();
            $time_out_time_check = $this->checktimeOut($request->input('time_out'));
            $time_out_check = date('Y-m-d', strtotime($request->input('time_out'))) ." " .date('H:i:s', strtotime($time_out_time_check));
            $check_attendance = Attendance::where('attendance_id',$request->input('save'))->first();

        //if invalid time out
        if (date('H:i:s', strtotime($check_attendance->time_in))>=date('H:i:s', strtotime($time_out_check))) {
            return back()->with('found','Record Not Saved!, Invalid time out');
        }
        else {
            DB::beginTransaction();
            try {
                $undertime = "";
                $overtime = "";
                $hours_worked = "";

                //check if currently on cto
                $hasCTO=$this->hasCTO($currentDate,auth()->user()->id);
                
                //if morning cto
                $check_under="";
                if (strpos($hasCTO, 'morning') !== false) {

                   
                    $undertime = $this->checktimeoutcto($time_out_check,'morning');  
                    $overtime = '00:00:00';
                    $hours_worked = '00:00:00';
                }
                //if afternoon cto
                else if (strpos($hasCTO, 'afternoon') !== false)
                {
                 
                    $undertime = $this->checktimeoutcto($time_out_check,'afternoon');
                    $overtime = '00:00:00';
                    $hours_worked = '00:00:00';
                }
                //if no cto
                else
                {
                    $check_for_undertime = $this->checkUndertime(
                        $check_attendance->time_in,
                        $time_out_check
                    );
                   
                    $hours_worked = $this->checkHoursWorked(
                        $check_attendance->time_in,
                        $time_out_check
                    );
                    if (str_contains($check_for_undertime, '-')) {
                        $undertime = $check_for_undertime;
                        $overtime = '00:00:00';
                    } else {
                        $undertime = '00:00:00';
                        $overtime = $check_for_undertime;
                    }

                }
                
				
                //get system settings for time out
                $time_out_settings = AttendanceSetting::select('system_setting_value')
                    ->where('system_setting_name','time out')
                    ->first();
                
                if($time_out_settings->system_setting_value == 'onsite') {
                    $work_arrangement_flag=$this->isOffice($request->input('save'),$request->input('public_ip_timeout'));
                    if(!$work_arrangement_flag) {
                    return back()->with('found','You are In Office today. Please connect to the ILS Network to timeout');
                    }
                }				

                $Attendance = Attendance::find($request->input('save'));
                $Attendance->accomplishment = $Attendance->accomplishment."\n".$request->input('accomplishment');
                $Attendance->time_status = $request->input('time_status');
                $Attendance->time_out = date('Y-m-d H:i:s',strtotime($time_out_check));
                
                $Attendance->undertime = $undertime;
                $Attendance->overtime = $overtime;
                $Attendance->hours_worked = $hours_worked;
                $Attendance->save();

                $show_out = date('F j, Y, g:i a', strtotime($time_out_check));

                //commit all changes
                DB::commit();
            } catch (\Exception $e) {

                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                return back()->with('found','Execution Error. Record Not Saved! Please contact the administrator');
            }
            return back()
                ->with('success', 'TIME OUT SUCCESSFUL: ' . $show_out)
                ->with('statusMark', true)
                ->with('time_in', $rec->time_in)
                ->with('time_out', $rec->time_out);
            }
        }

        //if not working day
        else
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

            
            $rec = DB::table($table_name)
            ->where('employee_id', auth()->user()->id)
            ->first();

        $time_out_time_check = $request->input('time_out');

        $time_out_check =
            date('Y-m-d', strtotime($request->input('time_out'))) .
            " " .
            date('H:i:s', strtotime($time_out_time_check));

        $check_attendance = DB::table($table_name)
        ->where('attendance_id',$request->input('save'))
        ->first();

        if (
            date('H:i:s', strtotime($check_attendance->time_in)) >=
            date('H:i:s', strtotime($time_out_check))
        ) {
            return back()->with(
                'found',
                'Record Not Saved!, Invalid time out '
            );
        } else {
            DB::beginTransaction();
            try {
                $check_for_undertime = $this->checkUndertime(
                    $check_attendance->time_in,
                    $time_out_check
                );
                $undertime = "";
                $overtime = "";
                $hours_worked = $this->checkHoursWorked(
                    $check_attendance->time_in,
                    $time_out_check
                );
                if (str_contains($check_for_undertime, '-')) {
                    $undertime = $check_for_undertime;
                    $overtime = '00:00:00';
                } else {
                    $undertime = '00:00:00';
                    $overtime = $check_for_undertime;
                }
                $Attendance =DB::table($table_name)
                ->where('attendance_id', $request->input('save'))
                ->update([
                    'accomplishment' => $request->input('accomplishment'),
                    'time_status' => 'timed_out',
                    'time_out' => date('Y-m-d H:i:s',strtotime($time_out_check)),
                    'hours_worked' => $hours_worked,
                ]);
                

                $show_out = date('F j, Y, g:i a', strtotime($time_out_check));
                DB::commit();
            } catch (\Exception $e) {
                //rollback all transactions made to database
                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                //redirect back to home with error
                return back()->with(
                    'found',
                    'Execution Error. Record Not Saved! Please contact the administrator'
                );
            }
            return back()
                ->with('success', 'TIME OUT SUCCESSFUL: ' . $show_out)
                ->with('statusMark', true)
                ->with('time_in', $rec->time_in)
                ->with('time_out', $rec->time_out);
        }

        }



    }
    public function export(request $request)
    {
        $id = $request->get('id');
        $from = $request->input('from');
        $to = $request->input('to');
        return Excel::download(
            new AttendanceExport($id, $from, $to),
            'attendance.xlsx'
        );
        //return (new AttendanceExport($id))->download('attendance.xlsx');
    }
    function checkshiftType($emp_id, $time_in)
    {
        $employee = DB::table('employees')
            ->where('employee_id', $emp_id)
            ->first();
        $f_time_in = "";
        if (strtotime($time_in) < strtotime("7:00:00")) {
            if ($employee->shift == "regular") {
                $f_time_in = "7:00:00";
            } else {
                $f_time_in = $time_in;
            }
        } else {
            $f_time_in = $time_in;
        }
        return $f_time_in;
    }
    function checkHoursWorked($timein, $timeout)
    {
        $break_duration = Carbon::parse(strtotime("01:00:00"))->tz('Asia/Manila');
        $lunch_start = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $lunch_end = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
        $WorkingHours = Carbon::parse(strtotime("08:00:00"))->tz('Asia/Manila');
        $LastTimeOut = Carbon::parse(strtotime("18:30:00"))->tz('Asia/Manila');
        $startTime = Carbon::parse(strtotime(date("H:i:s", strtotime($timein))))->tz('Asia/Manila');
        $endTime = Carbon::parse(strtotime(date("H:i:s", strtotime($timeout))))->tz('Asia/Manila');
        $interval = "00:00:00";
        $undertime = "";
        $hours_worked = "";
		
        if (strtotime($startTime) <= strtotime($lunch_start) && strtotime($endTime) <= strtotime($lunch_start)) {
            //no minus
            //hours worked = start - end
            //interval = hours worked - working hours
            //half day morning
            $interval = $startTime->diff($endTime)->format("%H:%I:00");
            //$interval = Carbon::parse($hours_worked)->tz('Asia/Manila')->diff($WorkingHours)->format("%H:%I:00");
        } elseif (strtotime($startTime) <= strtotime($lunch_start) && strtotime($endTime) > strtotime($lunch_start)) {
            if (strtotime($endTime) >= strtotime($lunch_end)) {
                //minus one hour
                //hours worked = start - end
                //interval = hours worked - working hours - break_duration
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($break_duration)
                    ->format("%H:%I:00");
            } elseif (strtotime($endTime) < strtotime($lunch_end)) {
                //dynamic
                //hours worked = start - end
                //lunchbreak = end - 12
                //interval = hours worked - working hours - lunchbreak
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $lunchbreak = $lunch_start->diff($endTime)->format("%H:%I:00");
                $interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($lunchbreak)
                    ->format("%H:%I:00");
            }
        } elseif (strtotime($startTime) > strtotime($lunch_start) && strtotime($endTime) > strtotime($lunch_start)) {
            if (
                strtotime($startTime) >= strtotime($lunch_end) &&
                strtotime($endTime) >= strtotime($lunch_end)
            ) {
                //no minus
                //hours worked = start - end
                //interval = hours worked - working hours
                //half day afternoon
                $interval = $startTime->diff($endTime)->format("%H:%I:00");
                //$interval = Carbon::parse($hours_worked)->tz('Asia/Manila')->diff($WorkingHours)->format("%H:%I:00");
            } elseif (
                strtotime($startTime) < strtotime($lunch_end) &&
                strtotime($endTime) > strtotime($lunch_end)
            ) {
                //dynamic
                //hours worked = start - end
                //lunchbreak = end - 12
                //interval = hours worked - working hours - lunchbreak
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $lunchbreak = $lunch_start
                    ->diff($startTime)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($lunchbreak)
                    ->format("%H:%I:00");
            } elseif (
                strtotime($startTime) < strtotime($lunch_end) &&
                strtotime($endTime) < strtotime($lunch_end)
            ) {
                //auto under
                //interval = 8:00:00
                $interval = "-08:00:00";
            }
        }
        return $interval;
    }

    function checkLate($time_in)
    {
        $currentTime = strtotime($time_in);
        $startTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($time_in)))
        )->tz('Asia/Manila');
        $endTime = Carbon::parse(strtotime("09:30:00"))->tz('Asia/Manila');
        $break_duration = Carbon::parse(strtotime("01:00:00"))->tz(
            'Asia/Manila'
        );
        $lunch_start = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $lunch_end = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
        $interval = "";

        if ($currentTime <= strtotime("12:00:00")) {
            if ($currentTime <= strtotime("09:30:00")) {
                //on time
                $interval = "00:00:00";
            } else {
                //no minus
                //late = start time - 10:00:00
                $interval = $startTime->diff($endTime)->format("%H:%I:00");
            }
        } elseif ($currentTime > strtotime("12:00:00")) {
            if ($currentTime >= strtotime("13:00:00")) {
                //minus 1 hour
                //late = start time - 10:00:00 - 1:00:00
                $temp_interval = $startTime
                    ->diff($break_duration)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($temp_interval)
                    ->tz('Asia/Manila')
                    ->diff($endTime)
                    ->format("%H:%I:00");
            } elseif ($currentTime < strtotime("13:00:00")) {
                //dynamic
                $temp_time_in = $startTime
                    ->diff($lunch_start)
                    ->format("%H:%I:00");
                $temp_interval = Carbon::parse($temp_time_in)
                    ->tz('Asia/Manila')
                    ->diff($startTime)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($temp_interval)
                    ->tz('Asia/Manila')
                    ->diff($endTime)
                    ->format("%H:%I:00");
            }
        }
        return $interval;
    }


    function checkLateAfternoon($time_in)
    {
        $currentTime = strtotime($time_in);
        $startTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($time_in)))
        )->tz('Asia/Manila');
        $endTime = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
      
        if ($currentTime <= strtotime("13:00:00")) {
            
                //on time
                $interval = "00:00:00";
            
        } elseif ($currentTime > strtotime("13:00:00")) {

            $interval = $startTime->diff($endTime)->format("%H:%I:00");
        }
        return $interval;
    }








    function checktimeOut($timeout)
    {
        if (date('H:i:s', strtotime($timeout))>=date('H:i:s', strtotime("18:30:00"))) {
            $f_timeout = "18:30:00";
        } else {
            $f_timeout = $timeout;
        }
        return $f_timeout;
    }

    function checktimeoutcto($timeout,$day)
    {
        $timeout_input = strtotime($timeout);

        $endTime = Carbon::parse(strtotime(date("H:i:s", strtotime($timeout))));
        $morning_end = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $afternoon_end = Carbon::parse(strtotime("16:00:00"))->tz('Asia/Manila');

        if($day=="morning")
        {
            if ($timeout_input >= strtotime("16:00:00")) {
                    //on time
                    $interval = "00:00:00";   
            } 
            elseif ($timeout_input < strtotime("16:00:00")) 
            {
                $interval = $afternoon_end->diff($endTime)->format("%H:%I:00");
               
                if($interval!="00:00:00")
                {
                    

                    if($afternoon_end<=$endTime)
                    {
                        $interval = "00:00:00";
                    }
                    else
                    {
                        $interval = "-" . $interval;
                    }
                    
                }   
                
            }
        }

        else if($day=="afternoon")
        {
            if ($timeout_input >= strtotime("12:00:00")) {
                //on time
                $interval = "00:00:00";   
            } 
            elseif ($timeout_input < strtotime("12:00:00")) 
            {
                $interval = $morning_end->diff($endTime)->format("%H:%I:00");

                
                    if($interval!="00:00:00")
                    {
                        

                        if($afternoon_end<=$endTime)
                        {
                            $interval = "00:00:00";
                        }
                        else
                        {
                            $interval = "-" . $interval;
                        }
                        
                    }
                

               
            }

        }

        return $interval;
    }


    function checkUndertime($timein, $timeout)
    {
        $break_duration = Carbon::parse(strtotime("01:00:00"))->tz('Asia/Manila');
        $lunch_start = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $lunch_end = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
        $WorkingHours = Carbon::parse(strtotime("08:00:00"))->tz('Asia/Manila');
        $LastTimeOut = Carbon::parse(strtotime("18:30:00"))->tz('Asia/Manila');
        $startTime = Carbon::parse(strtotime(date("H:i:s", strtotime($timein))))->tz('Asia/Manila');
        $endTime = Carbon::parse(strtotime(date("H:i:s", strtotime($timeout))))->tz('Asia/Manila');
		
        $interval = "00:00:00";
        $undertime = "";
        $hours_worked = "";
        if (strtotime($startTime) <= strtotime($lunch_start) && strtotime($endTime) <= strtotime($lunch_start)) {
            //no minus
            //hours worked = start - end
            //interval = hours worked - working hours
            //half day morning
            $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
            $interval = Carbon::parse($hours_worked)
                ->tz('Asia/Manila')
                ->diff($WorkingHours)
                ->format("%H:%I:00");
            //identifier if - means undertime
            if (strtotime($hours_worked) < strtotime($WorkingHours)) {
				$interval = "-" . $interval;
            }
        } 
		else if (strtotime($startTime) <= strtotime($lunch_start) && strtotime($endTime) > strtotime($lunch_start)) {
            if (strtotime($endTime) >= strtotime($lunch_end)) {
                //minus one hour
                //hours worked = start - end
                //interval = hours worked - working hours - break_duration
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $temp_interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($break_duration)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($temp_interval)
                    ->tz('Asia/Manila')
                    ->diff($WorkingHours)
                    ->format("%H:%I:00");
                //identifier if - means undertime
                if (strtotime($temp_interval) < strtotime($WorkingHours)) {
                    $interval = "-" . $interval;
                }
            } 
			
			else if (strtotime($endTime) < strtotime($lunch_end)) {
                //dynamic
                //hours worked = start - end
                //lunchbreak = end - 12
                //interval = hours worked - working hours - lunchbreak
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $lunchbreak = $lunch_start->diff($endTime)->format("%H:%I:00");
                $temp_interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($lunchbreak)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($temp_interval)
                    ->tz('Asia/Manila')
                    ->diff($WorkingHours)
                    ->format("%H:%I:00");
                //identifier if - means undertime
                if (strtotime($temp_interval) < strtotime($WorkingHours)) {
                    $interval = "-" . $interval;
                }
            }
			
        } elseif (strtotime($startTime) > strtotime($lunch_start) && strtotime($endTime) > strtotime($lunch_start)) {
            if (strtotime($startTime) >= strtotime($lunch_end) && strtotime($endTime) >= strtotime($lunch_end)) {
                //no minus
                //hours worked = start - end
                //interval = hours worked - working hours
                //half day afternoon
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($WorkingHours)
                    ->format("%H:%I:00");
                //identifier if - means undertime
                if (strtotime($hours_worked) < strtotime($WorkingHours)) {
                    $interval = "-" . $interval;
                }
            } 
			
			elseif (strtotime($startTime) < strtotime($lunch_end) && strtotime($endTime) > strtotime($lunch_end)) {
                //dynamic
                //hours worked = start - end
                //lunchbreak = end - 12
                //interval = hours worked - working hours - lunchbreak
                $hours_worked = $startTime->diff($endTime)->format("%H:%I:00");
                $lunchbreak = $lunch_start
                    ->diff($startTime)
                    ->format("%H:%I:00");
                $temp_interval = Carbon::parse($hours_worked)
                    ->tz('Asia/Manila')
                    ->diff($lunchbreak)
                    ->format("%H:%I:00");
                $interval = Carbon::parse($temp_interval)
                    ->tz('Asia/Manila')
                    ->diff($WorkingHours)
                    ->format("%H:%I:00");
                //identifier if - means undertime
                if (strtotime($temp_interval) < strtotime($WorkingHours)) {
                    $interval = "-" . $interval;
                }
            } 
			
			else if (strtotime($startTime) < strtotime($lunch_end) && strtotime($endTime) < strtotime($lunch_end)) {
                //auto under
                //interval = 8:00:00
                $interval = "-08:00:00";
            }
        }
        return $interval;
    }


    public function isWorkingDay($date,$id)
    {
        //convert date to day name i.e Saturday
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

    public function hasCTO($date,$id)
    {

        //check if has cto na halfday
    $cto = DB::table('attendances')
        ->select('attendance_id','time_status')
        ->where('employee_id',$id)
        ->where('time_in', $date) //current date
        ->where(function($q) {
            $q->where('time_status', 'cto-morning')
              ->orWhere('time_status', 'cto-afternoon');
        })
        ->get();
        

        if ($cto->isNotEmpty()) 
        {
            $time_status="";
            foreach($cto as $at)
            {
                $time_status=$at->time_status;   
            }
            return $time_status;          
        } 
        
        else 
        {
            return false;
        }

    }

    public function isOffice($attendance_id,$public_ip)
    {
        $status=true;
        //get work status
        $work_setting = WorkSetting::select('status')->where('attendance_id',$attendance_id)->first();
        $work_status = $work_setting->status;

        if($work_status=="in office") {

            $work_envi = $this->checkIp($public_ip);

            if(!$work_envi)
            {
                $status=false;
            }
        }

        return $status;
    }

    public function getPublicIp(Request $request) {

        /*
        $ip = "";
        try{
            $ip = file_get_contents('https://api.ipify.org/?callback=getIP');
        }
        catch (\Exception $e) {
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();

            return back()->with('found','Execution Error. Kindly reload your browser and contact the administrator');
        }*/

        $ip=$request->input('public_ip_timeout_text');

        return $ip;
    }

    public function checkIp($public_ip) {

        $status="";

        $ils_public_ip = array("119.92.225.0","119.92.225.1","119.92.225.2","119.92.225.3","119.92.225.4",
        "119.92.225.5","119.92.225.6","119.92.225.7","119.92.225.8","119.92.225.9",
        "119.92.225.10","119.92.225.11","119.92.225.12","119.92.225.13","119.92.225.14", 
        "116.50.187.160","116.50.187.161","116.50.187.162","116.50.187.163","116.50.187.164",
        "116.50.187.165","116.50.187.166","116.50.187.167","116.50.187.168","116.50.187.169",
        "116.50.187.170","116.50.187.171","116.50.187.172","116.50.187.173","116.50.187.174",
        "116.50.187.175","116.50.187.176","116.50.187.177","116.50.187.178","116.50.187.179",
        "116.50.187.180","116.50.187.181","116.50.187.182","116.50.187.183","116.50.187.184",
        "116.50.187.185","116.50.187.186","116.50.187.187","116.50.187.188","116.50.187.189",
        "116.50.187.190");
		
		$flipped_public_ip = array_flip($ils_public_ip);
		
		if (isSet($flipped_public_ip[$public_ip])) {
			$status=true;
		}
		
		else {
		    $status=false;
		}
		

		/*
        if (in_array($public_ip, $ils_public_ip)) {
            $status=true;
        }*/
		
		
		


        return $status;
    }

    


}
