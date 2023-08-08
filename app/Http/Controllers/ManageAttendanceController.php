<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Attendance;
use App\WorkSetting;
use App\Other_attendances;
use App\ObaoAttendances;
use App\Employee;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\ErrorLog;
class ManageAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendance = DB::table('users')
            ->join('attendances', 'attendances.employee_id', '=', 'users.id')
            ->leftjoin('work_settings', 'attendances.attendance_id', '=', 'work_settings.attendance_id')
     //       ->select('attendances.time_in','attendances.time_out','attendances.accomplishment','attendances.status','work_settings.status as wstatus')
            ->select(
                'users.name',
                'users.id',
                'attendances.attendance_id',
                'attendances.time_in',
                'attendances.status',
                'attendances.time_status',
                'work_settings.status as wstatus'
            )
            ->orderBy('attendances.time_in', 'DESC')
            ->paginate(20);

    

        $oed = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Office of the Executive Director (OED)'
            )
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        $erd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where('employees.division', 'Employment Research Division (ERD)')
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        $lssrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Labor and Social Relations Research Division (LSRRD)'
            )
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        $wwrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Workers Welfare Research Division (WWRD)'
            )
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        $apd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Advocacy and Pulications Division (APD)'
            )
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        $fad = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Finance and Administrative Division (FAD)'
            )
            ->where('users.is_disabled',0)
            ->where('users.id','!=',auth()->user()->id)
            ->get();
        // return view('admin.attendance.index')->with('attendance',$attendance);
        return view(
            'admin.attendance.index',
            compact('attendance', 'oed', 'erd', 'lssrd', 'wwrd', 'apd', 'fad')
        );
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

        $input_date=$request->input('time_in_date');

        $workingFlag=$this->isWorkingDay($input_date,$request->get('employee'));

        if($workingFlag) //is a working day
        {
            $result = DB::table('attendances')
            ->where('employee_id', $request->get('employee'))
            ->where('time_status','timed_in')
            ->whereDate('time_in', $request->input('time_in_date'))
            ->first();
        if (
            strtotime($request->input('time_in_time')) >= strtotime("19:00:00")
        ) {
            return back()->with('error', 'Too late to time in.');
        }
        if (empty($result)) {
            DB::beginTransaction();
            try {
                $time_in_check_late =
                    $request->input('time_in_date') .
                    " " .
                    $request->input('time_in_time');
                $time_in_final = $this->checkshiftType(
                    $request->get('employee'),
                    $request->input('time_in_time')
                );


                $hasCTO=$this->hasCTO($input_date,$request->get('employee'));

                $check_late="";
                //if morning cto nya, use checking of late in the afternoon function
                if (strpos($hasCTO, 'morning') !== false) {

                    $check_late=$this->checkLateAfternoon(date("H:i:s", strtotime($request->input('time_in_time'))));   
                }
                //if afternoon cto nya, use ordinary late checker
                else
                {
                    $check_late=$this->checkLate(date("H:i:s", strtotime($request->input('time_in_time'))));

                }


                $employee = Attendance::create([
                    'employee_id' => $request->get('employee'),
                    'status' => 'present',
                    'time_status' => 'timed_in',
                    'time_in' =>
                        $request->input('time_in_date') . " " . $time_in_final,
                     'late' => $check_late,
                ]);

                WorkSetting::create([
                    'attendance_id' => $employee->attendance_id,
                    'status' => $request->get('work_setting'),
                    ]);



                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                return back()->with(
                    'error',
                    'Execution Error. Record Not Saved! Please contact the administrator'
                );
            }
        } else {
            return back()->with(
                'error',
                'Attendance already exists on that date.'
            );
        }
        return back()->with('success', 'Attendance Added! (General)');
            
        }

        else
        {

            $obaoFlag=$this->isObao($input_date,$request->get('employee'));
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



            $result = DB::table($table_name)
            ->where('employee_id', $request->get('employee'))
            //->where('time_status','timed_in')
            ->whereDate('time_in', $request->input('time_in_date'))
            ->first();
        
        if (empty($result)) {
            DB::beginTransaction();
            try {
                $time_in_check_late =
                    $request->input('time_in_date') .
                    " " .
                    $request->input('time_in_time');
                $time_in_final = $this->checkshiftType(
                    $request->get('employee'),
                    $request->input('time_in_time')
                );

                
                $employee = DB::table($table_name)->insert([
                    'employee_id' => $request->get('employee'),
                    'status' => 'present',
                    'time_status' => 'timed_in',
                    'time_in' => $request->input('time_in_date') . " " . $time_in_final,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                return back()->with(
                    'error',
                    'Execution Error. Record Not Saved! Please contact the administrator'
                );
            }
        } else {
            return back()->with(
                'error',
                'Attendance already exists on that date.'
            );
        }
        return back()->with('success', 'Attendance Added '.$attendance_type.'!');

        }





       
    }
    public function accomplishment(Request $request)
    {

        $input_date=$request->input('time_out_date');

        $workingFlag=$this->isWorkingDay($input_date,$request->get('employee'));

        if($workingFlag)
        {
            DB::beginTransaction();
            try {
                $result = DB::table('attendances')
                    ->where('employee_id', $request->get('employee'))
                    ->where('time_status','timed_in')
                    ->whereDate('time_in', $request->input('time_out_date'))
                    ->first();
                if (!empty($result)) {
                    $time_out_check =$request->input('time_out_date') ." " .$this->checktimeOut($request->input('time_out_time'));
                    if (
                        date('H:i:s', strtotime($result->time_in)) >=
                        date('H:i:s', strtotime($time_out_check))
                    ) {
                        return back()->with('error', 'Invalid time out ');
                    }


                    $undertime = "";
                    $overtime = "";
                    $hours_worked = "";

                    //check if currently on cto
                    $hasCTO=$this->hasCTO($input_date,$request->get('employee'));

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

                    else
                    {
                        $check_for_undertime = $this->checkUndertime(
                            $result->time_in,
                            $time_out_check
                        );
                       
                        $hours_worked = $this->checkHoursWorked(
                            $result->time_in,
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



                    $Attendance = DB::table('attendances')
                        ->where('attendance_id', $result->attendance_id)
                        ->update([
                            'accomplishment' => $request->input('accomplishment'),
                            'time_status' => 'timed_out',
                            'time_out' => $time_out_check,
                            'undertime' => $undertime,
                            'overtime' => $overtime,
                            'hours_worked' => $hours_worked,
                        ]);



                } else {
                    return back()->with(
                        'error',
                        'Attendance doesnt exists on that date.'
                    );
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $log = new ErrorLog();
                $log->message = $e->getMessage();
                $log->file = $e->getFile();
                $log->line = $e->getLine();
                $log->url = $_SERVER['REQUEST_URI'];
                $log->save();
                return back()->with(
                    'error',
                    'Execution Error. Record Not Saved! Please contact the administrator'
                );
            }
            return back()->with('success', 'Attendance Added (General)!');

        }

        else
        {

            $obaoFlag=$this->isObao($input_date,$request->get('employee'));
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

             DB::beginTransaction();
        try {
            $result = DB::table($table_name)
                ->where('employee_id', $request->get('employee'))
                ->whereDate('time_in', $request->input('time_out_date'))
                ->first();
            if (!empty($result)) {
                $time_out_check =
                    $request->input('time_out_date') .
                    " " .date('H:i:s', strtotime($request->input('time_out_time')));
                if (
                    date('H:i:s', strtotime($result->time_in)) >=
                    date('H:i:s', strtotime($time_out_check))
                ) {
                    return back()->with('error', 'Invalid time out ');
                }
                $check_for_undertime = $this->checkUndertime(
                    $result->time_in,
                    $time_out_check
                );
                $undertime = "";
                $overtime = "";
                $hours_worked = $this->checkHoursWorked(
                    $result->time_in,
                    $time_out_check
                );
                if (str_contains($check_for_undertime, '-')) {
                    $undertime = $check_for_undertime;
                    $overtime = '00:00:00';
                } else {
                    $undertime = '00:00:00';
                    $overtime = $check_for_undertime;
                }
                $Attendance = DB::table($table_name)
                    ->where('attendance_id', $result->attendance_id)
                    ->update([
                        'accomplishment' => $request->input('accomplishment'),
                        'time_status' => 'timed_out',
                        'time_out' => $time_out_check,
                        'hours_worked' => $hours_worked,
                    ]);
            } else {
                return back()->with(
                    'error',
                    'Attendance doesnt exists on that date.'
                );
            }





            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        return back()->with('success', 'Attendance Added ('.$attendance_type.')!');

        }
       
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $attendance = DB::table('users')
            ->join('attendances', 'attendances.employee_id', '=', 'users.id')
            ->select(
                'users.name',
                'attendances.attendance_id',
                'attendances.time_in',
                'attendances.status',
                'attendances.time_status'
            )
            ->whereBetween(DB::raw('DATE(time_in)'), [
                $request->input('from'),
                $request->input('to'),
            ])
            ->get();
        $oed = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Office of the Executive Director (OED)'
            )
            ->get();
        $erd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where('employees.division', 'Employment Research Division (ERD)')
            ->get();
        $lssrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Labor and Social Relations Research Division (LSRRD)'
            )
            ->get();
        $wwrd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Workers Welfare Research Division (WWRD)'
            )
            ->get();
        $apd = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Advocacy and Pulications Division (APD)'
            )
            ->get();
        $fad = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.middlename',
                'employees.lastname',
                'employees.extname',
                'employees.position'
            )
            ->where(
                'employees.division',
                'Finance and Administrative Division (FAD)'
            )
            ->get();
        // return view('admin.attendance.index')->with('attendance',$attendance);
        return view(
            'admin.attendance.index',
            compact('attendance', 'oed', 'erd', 'lssrd', 'wwrd', 'apd', 'fad')
        );
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
        try {
            $attendance = Attendance::find($id);
            $attendance->status = $request->get('update');
            $attendance->time_status = $request->get('update');
            $attendance->accomplishment = $request->get('update');
            $attendance->undertime = "00:00:00";
            $attendance->overtime = "00:00:00";
            $attendance->hours_worked = "00:00:00";
            $attendance->late = "00:00:00";
            $attendance->save();

            $work_setting = WorkSetting::where("attendance_id",$id)->delete();

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
            return redirect('/manageattendance')->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        return redirect('manageattendance')->with(
            'success',
            'Attendance marked as absent'
        );
    }

    public function markpresent(Request $request)
    {
        try {
            $attendance = Attendance::find(
                $request->input('present_attendance_id')
            );
            $attendance->status = "present";
            $attendance->time_status = "timed_out";
            $attendance->accomplishment = $request->input('accomplishment');
            $time_in_final = $this->checkshiftType(
                $attendance->employee_id,
                $request->input('time_in_time')
            );
            $new_time_in =
                date('Y-m-d', strtotime($attendance->time_in)) .
                " " .
                $time_in_final;
            $new_time_out =
                date('Y-m-d', strtotime($attendance->time_in)) .
                " " .
                $this->checktimeOut($request->input('time_out_time'));
            if (
                date('Y-m-d H:i:s', strtotime($new_time_in)) >=
                date('Y-m-d H:i:s', strtotime($new_time_out))
            ) {
                return back()->with('error', 'Invalid time out ');
            }
            $check_for_undertime = $this->checkUndertime(
                date('Y-m-d H:i:s', strtotime($new_time_in)),
                date('Y-m-d H:i:s', strtotime($new_time_out))
            );
            $undertime = "";
            $overtime = "";
            $hours_worked = $this->checkHoursWorked(
                date('Y-m-d H:i:s', strtotime($new_time_in)),
                date('Y-m-d H:i:s', strtotime($new_time_out))
            );
            if (str_contains($check_for_undertime, '-')) {
                $undertime = $check_for_undertime;
                $overtime = '00:00:00';
            } else {
                $undertime = '00:00:00';
                $overtime = $check_for_undertime;
            }
            $attendance->time_in = date('Y-m-d H:s:i', strtotime($new_time_in));
            $attendance->time_out = date(
                'Y-m-d H:s:i',
                strtotime($new_time_out)
            );
            $attendance->undertime = $undertime;
            $attendance->overtime = $overtime;
            $attendance->hours_worked = $hours_worked;
            $attendance->late = $this->checkLate(
                $request->input('time_in_time')
            );
            $attendance->save();


            WorkSetting::create([
                'attendance_id' =>  $request->input('present_attendance_id'),
                'status' => $request->get('work_setting'),
                ]);

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
            return redirect('/manageattendance')->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        return redirect('manageattendance')->with(
            'success',
            'Attendance marked as present'
        );
    }

    public function autoabsent(Request $request)
    {
        $result = Attendance::where(
            'employee_id',
            '=',
            $request->get('employee')
        )
            ->whereDate('time_in', $request->input('time_in_date'))
            ->get();
        if (!$result->isEmpty()) {
            return redirect('/manageattendance')->with(
                'error',
                'Employee already has a record on ' .
                    $request->input('time_in_date')
            );
        } else {
            DB::beginTransaction();
            try {
                //$user_id = User::select('id')->all();
                //$id = User::pluck('id')->all();
                //$user_id = User::get();
                /* $users=DB::table('users')
                ->join('attendances','attendances.employee_id','=','users.id')
                ->select('users.id')
                ->whereNotIn('attendances.employee_id',$user_id->id)
                ->whereDate('attendances.time_in', '=', date('Y-m-d',strtotime($request->input('time_in_date'))))
                ->get();*/
                Attendance::create([
                    'employee_id' => $request->get('employee'),
                    'status' => 'absent',
                    'time_status' => 'absent',
                    'late' => '00:00:00',
                    'accomplishment' => 'absent',
                    'undertime' => "00:00:00",
                    'overtime' => "00:00:00",
                    'hours_worked' => "00:00:00",
                    'time_in' => date(
                        'Y-m-d H:i:s',
                        strtotime($request->input('time_in_date'))
                    ),
                    'time_out' => date(
                        'Y-m-d H:i:s',
                        strtotime($request->input('time_in_date'))
                    ),
                ]);
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
                return redirect('/manageattendance')->with(
                    'error',
                    'Execution Error. Record Not Saved!'
                );
            }
            return redirect('manageattendance')->with(
                'success',
                'Absences added.'
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($attendance_id = null)
    {
        Attendance::where('attendance_id', $attendance_id)->delete();
        return redirect('manageattendance');
    }
    public function destroyattendance(Request $request)
    {
        //Find a user with a given id and delete
        $id = $request->input('delete_attendance_id');
        DB::beginTransaction();
        try {
            Attendance::where('attendance_id', $id)->delete();
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
            return redirect('/manageattendance')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        return redirect('manageattendance')->with(
            'success',
            'record successfully deleted.'
        );
    }
    function checkshiftType($emp_id, $time_in)
    {
        $employee = DB::table('employees')
            ->where('employee_id', $emp_id)
            ->first();
        $shift = $employee->shift;
        $f_time_in = "";
        if (strtotime($time_in) < strtotime("7:00:00")) {
            if ($shift == "regular") {
                $f_time_in = "7:00:00";
            } else {
                $f_time_in = $time_in;
            }
        } else {
            $f_time_in = $time_in;
        }
        return $f_time_in;
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
    function checktimeOut($timeout)
    {
        if (
            date('H:i:s', strtotime($timeout)) >=
            date('H:i:s', strtotime("19:00:00"))
        ) {
            $f_timeout = "19:00:00";
        } else {
            $f_timeout = $timeout;
        }
        return $f_timeout;
    }
    function checkHoursWorked($timein, $timeout)
    {
        $break_duration = Carbon::parse(strtotime("01:00:00"))->tz(
            'Asia/Manila'
        );
        $lunch_start = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $lunch_end = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
        $WorkingHours = Carbon::parse(strtotime("08:00:00"))->tz('Asia/Manila');
        $LastTimeOut = Carbon::parse(strtotime("19:00:00"))->tz('Asia/Manila');
        $startTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($timein)))
        )->tz('Asia/Manila');
        $endTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($timeout)))
        )->tz('Asia/Manila');
        $interval = "00:00:00";
        $undertime = "";
        $hours_worked = "";
        if (
            strtotime($startTime) <= strtotime($lunch_start) &&
            strtotime($endTime) <= strtotime($lunch_start)
        ) {
            //no minus
            //hours worked = start - end
            //interval = hours worked - working hours
            //half day morning
            $interval = $startTime->diff($endTime)->format("%H:%I:00");
            //$interval = Carbon::parse($hours_worked)->tz('Asia/Manila')->diff($WorkingHours)->format("%H:%I:00");
        } elseif (
            strtotime($startTime) <= strtotime($lunch_start) &&
            strtotime($endTime) > strtotime($lunch_start)
        ) {
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
        } elseif (
            strtotime($startTime) > strtotime($lunch_start) &&
            strtotime($endTime) > strtotime($lunch_start)
        ) {
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
        $break_duration = Carbon::parse(strtotime("01:00:00"))->tz(
            'Asia/Manila'
        );
        $lunch_start = Carbon::parse(strtotime("12:00:00"))->tz('Asia/Manila');
        $lunch_end = Carbon::parse(strtotime("13:00:00"))->tz('Asia/Manila');
        $WorkingHours = Carbon::parse(strtotime("08:00:00"))->tz('Asia/Manila');
        $LastTimeOut = Carbon::parse(strtotime("19:00:00"))->tz('Asia/Manila');
        $startTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($timein)))
        )->tz('Asia/Manila');
        $endTime = Carbon::parse(
            strtotime(date("H:i:s", strtotime($timeout)))
        )->tz('Asia/Manila');
        $interval = "00:00:00";
        $undertime = "";
        $hours_worked = "";
        if (
            strtotime($startTime) <= strtotime($lunch_start) &&
            strtotime($endTime) <= strtotime($lunch_start)
        ) {
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
        } elseif (
            strtotime($startTime) <= strtotime($lunch_start) &&
            strtotime($endTime) > strtotime($lunch_start)
        ) {
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
            } elseif (strtotime($endTime) < strtotime($lunch_end)) {
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
        } elseif (
            strtotime($startTime) > strtotime($lunch_start) &&
            strtotime($endTime) > strtotime($lunch_start)
        ) {
            if (
                strtotime($startTime) >= strtotime($lunch_end) &&
                strtotime($endTime) >= strtotime($lunch_end)
            ) {
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

}
