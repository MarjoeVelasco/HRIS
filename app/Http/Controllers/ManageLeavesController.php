<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Leaves;
use DB;
use App\Attendance;
use App\Employee;
use Carbon\Carbon;
use App\ErrorLog;
use Illuminate\Support\Facades\Mail;

use PhpOffice\PhpWord\TemplateProcessor;

class ManageLeavesController extends Controller
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
        DB::beginTransaction();
        try {
            $leaves = DB::table('users')
                ->join('leaves', 'leaves.employee_id', '=', 'users.id')
                ->select(
                    'leaves.leave_id',
                    'users.name',
                    'leaves.leave_type',
                    'leaves.details',
                    'leaves.created_at',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.note'
                )
                ->where('leaves.archive', '=', 0)
                ->orderBy('leaves.leave_id', 'desc')
                ->paginate(10);
            //->get();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error
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

        return view('admin.leaves.index')->with('leaves', $leaves);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leaves = DB::table('leaves')
            ->join(
                'employees',
                'leaves.employee_id',
                '=',
                'employees.employee_id'
            )
            ->select(
                'employees.extname',
                'employees.lastname',
                'employees.firstname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.*'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get();

        $supervisors = DB::table('leaves')
            ->join(
                'employees',
                'leaves.supervisor_id',
                '=',
                'employees.employee_id'
            )
            ->select(
                'employees.extname',
                'employees.lastname',
                'employees.firstname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.*'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get();

        return view('admin.leaves.view')
            ->with('leaves', $leaves)
            ->with('supervisors', $supervisors);
    }


    public function showcto($id)
    {
        $leaves = DB::table('leaves')
        ->join(
            'employees',
            'employees.employee_id',
            '=',
            'leaves.employee_id'
        )
        ->select(
            'employees.firstname',
            'employees.lastname',
            'employees.middlename',
            'employees.extname',
            'employees.position',
            'employees.sg',
            'leaves.inclusive_dates',
            'leaves.no_days',
            'leaves.leave_type',
            'leaves.details',
            'leaves.commutation',
            'leaves.status',
            'leaves.note',
            'leaves.date_approved',
            'leaves.supervisor_id',
            'leaves.approver_id',
            'leaves.signatory_id',
            'leaves.created_at'
        )
        ->where('leaves.leave_id', '=', $id)
        ->first();

        $date_filed=$leaves->created_at;
        $name=$leaves->firstname." ".$leaves->middlename." ".$leaves->lastname." ".$leaves->extname;
        $no_days=$leaves->no_days;
        $inclusive_dates=$leaves->inclusive_dates;
        $position=$leaves->position;
        $note=$leaves->note;

        $cert_as_of=$leaves->date_approved;
        $hours_earned="";
        $date_last_cert="";

        if($note!="Waiting for approval")
        {
            $temp = explode(",", $note);
            $hours_earned=$temp[0];
            $date_last_cert=$temp[1];
        }

        $supervisor = DB::table('employees')
        ->select(
            'firstname',
            'lastname',
            'middlename',
            'extname',
            'position'
        )
        ->where('employee_id', '=', $leaves->supervisor_id)
        ->first();

        $supervisor_name=$supervisor->firstname." ".$supervisor->middlename." ".$supervisor->lastname." ".$supervisor->extname;
        $supervisor_position=$supervisor->position;

        $hr = DB::table('employees')
        ->select(
            'firstname',
            'lastname',
            'middlename',
            'extname',
            'position'
        )
        ->where('employee_id', '=', $leaves->approver_id)
        ->first();

        $hr_name=$hr->firstname." ".$hr->middlename." ".$hr->lastname." ".$hr->extname;
        $hr_position=$hr->position;


        $signatory = DB::table('employees')
        ->select(
            'firstname',
            'lastname',
            'middlename',
            'extname',
            'position',
        )
        ->where('employee_id', '=', $leaves->signatory_id)
        ->first();

        $signatory_name=$signatory->firstname." ".$signatory->middlename." ".$signatory->lastname." ".$signatory->extname;
        $signatory_position=$signatory->position;


        return view('admin.leaves.viewcto',compact(
            'date_filed',
            'name',
            'position',
            'no_days',
            'inclusive_dates',
            'hr_name',
            'hr_position',
            'supervisor_name',
            'supervisor_position',
            'signatory_name',
            'signatory_position',
            'note',
            'cert_as_of',
            'hours_earned',
            'date_last_cert',

        )); 
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
        DB::beginTransaction();
        try {
            $leaves = Leaves::find($id);
            $leaves->note = "Application for leave approved!";
            $leaves->status = $request->get('update');
            $leaves->save();

            $startDate = date('Y-m-d', strtotime($leaves->from));
            $endDate = date('Y-m-d', strtotime($leaves->to));

            if ($leaves->status == 'approved') {
                while ($startDate <= $endDate) {
                    $canMark = true;
                    $attendance = Attendance::where('employee_id', $id)->get();

                    foreach ($attendance as $at) {
                        if ($startDate == date('Y-m-d', strtotime($at->time_in))) {
                            if ($at->status == 'absent') 
                            {
                                $at->status = 'on leave';
                                $at->save();
                            }
                            $canMark = false;
                            break;
                        } else {
                            $canMark = true;
                        }
                    }

                    if ($canMark) {
                        //remove attendance

                        Attendance::where('time_status','!=',$leave_type)
                        ->where('status','!=','on leave')
                        ->whereDate('time_in',date("Y-m-d", strtotime($startDate)))
                        ->where('employee_id',$employee_id)
                        ->delete();

                        $att = new Attendance();
                        $att->employee_id = $id;
                        $att->status = 'on leave';
                        $att->time_in = $startDate;
                        $att->time_out = $startDate;
                        $att->undertime = '00:00:00';
                        $att->overtime = '00:00:00';
                        $att->hours_worked = '00:00:00';
                        $att->late = '00:00:00';

                        $att->save();
                    }
                    $startDate = date(
                        'Y-m-d',
                        strtotime($startDate . '+1 day')
                    );
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error.
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

        return back()
            ->with('success', 'Leave Request Approved')
            ->with('leaves', $leaves);
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


    public function declinecto(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->input('id');
            $curDate = $request->input('curDate');
            
            $note = $request->input('hours_earned').','.$request->input('last_cert');
            $Leave = Leaves::find($id);
            $Leave->note = $note;
            $Leave->status = "Disapproved by HR";
            $Leave->date_approved = $curDate;
            $Leave->save();

            $leave_type = $Leave->leave_type;
            $inclusive_dates = $Leave->inclusive_dates;
            $employee_id = $Leave->employee_id;
            $details= $Leave->details;

            $inclusive_dates = explode(',', $inclusive_dates);

            for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {
                $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));

                DB::table('attendances')
                    ->where('employee_id', $employee_id)
                    ->where('status', 'on leave')
                    ->where('time_status', 'like','%'.$leave_type.'%')
                    ->whereDate('time_in', $insertedDate)
                    ->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error.
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

        return back()
            ->with('success', 'Leave Request Declined');
    }


    public function approvecto(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->input('id');
            $curDate = $request->input('curDate');
            
            $note = $request->input('hours_earned').','.$request->input('last_cert');
            $Leave = Leaves::find($id);
            $Leave->note = $note;
            $Leave->status = "Approved by HR";
            $Leave->date_approved = $curDate;
            $Leave->save();

          
            $leave_type = $Leave->leave_type;
            $inclusive_dates = $Leave->inclusive_dates;
            $employee_id = $Leave->employee_id;
            $details= $Leave->details;

         
            if($details=="whole day")
            {
                $inclusive_dates = explode(',', $inclusive_dates);

                for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {

                    $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));

                    //remove attendance
                    Attendance::where('time_status','!=',$leave_type)
                    ->where('status','!=','on leave')
                    ->whereDate('time_in',date("Y-m-d", strtotime($inclusive_dates[$i])))
                    ->where('employee_id',$employee_id)
                    ->delete();

                    //add leave to attendance
                    $att = new Attendance();
                    $att->employee_id = $employee_id;
                    $att->status = 'on leave';
                    $att->time_status = $leave_type;
                    $att->time_in = $insertedDate;
                    $att->time_out = $insertedDate;
                    $att->undertime = '00:00:00';
                    $att->overtime = '00:00:00';
                    $att->hours_worked = '00:00:00';
                    $att->late = '00:00:00';

                    $att->save();
                }

            }

            else
            { 
                $details = explode(',', $details);
                $date = $details[0];
                $day = $details[1];
                $inclusive_dates = explode(',', $inclusive_dates);

                for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {

                    $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));

                    if($insertedDate==$date)
                    {
                        $leave_type="cto-".$day;
                    }
                    else
                    {
                        $leave_type="cto";
                    }
                    $att = new Attendance();
                    $att->employee_id = $employee_id;
                    $att->status = 'on leave';
                    $att->time_status = $leave_type;
                    $att->time_in = $insertedDate;
                    $att->time_out = $insertedDate;
                    $att->undertime = '00:00:00';
                    $att->overtime = '00:00:00';
                    $att->hours_worked = '00:00:00';
                    $att->late = '00:00:00';

                    $att->save();
                }





                
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error.
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

        return back()
            ->with('success', 'Leave Request Approved');
    }

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try {
            $leaves = DB::table('users')
                ->join('leaves', 'leaves.employee_id', '=', 'users.id')
                ->select(
                    'leaves.leave_id',
                    'users.name',
                    'leaves.leave_type',
                    'leaves.details',
                    'leaves.inclusive_dates',
                    'leaves.created_at',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.note'
                )
                ->where('leaves.archive', '=', 0)
                ->orderBy('leaves.leave_id', 'desc')
                ->paginate(10);

            $id = $request->input('id');
            $curDate = $request->input('curDate');

            $vl = $request->input('remaining_vl');
            $sl = $request->input('remaining_sl');
            $slp = $request->input('remaining_slp');
            $total = $vl + $sl + $slp;

            $paid_days = $request->input('days_with_pay');
            $unpaid_days = $request->input('days_without_pay');
            $other_days =
                $request->input('others_days') .
                " " .
                $request->input('others_specify');

            $note =
                $vl .
                "," .
                $sl .
                "," .
                $slp .
                "," .
                $total .
                "," .
                $paid_days .
                "," .
                $unpaid_days .
                "," .
                $other_days;

            $Leave = Leaves::find($id);
            $Leave->note = $note;
            $Leave->status = "Approved by HR";
            $Leave->date_approved = $curDate;
            $Leave->save();

            $leave_type = $Leave->leave_type;
            $inclusive_dates = $Leave->inclusive_dates;
            $employee_id = $Leave->employee_id;
            $start_maternity_date = date(
                'Y-m-d',
                strtotime($Leave->start_date)
            );
            $end_maternity_date = date('Y-m-d', strtotime($Leave->end_date));

            if ($leave_type != "maternity leave") {
                $inclusive_dates = explode(',', $inclusive_dates);

                for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {
                    $insertedDate = date('Y-m-d',strtotime($inclusive_dates[$i]));

                    Attendance::where('time_status','!=',$leave_type)
                    ->where('status','!=','on leave')
                    ->whereDate('time_in',date("Y-m-d", strtotime($inclusive_dates[$i])))
                    ->delete();

                    $att = new Attendance();
                    $att->employee_id = $employee_id;
                    $att->status = 'on leave';
                    $att->time_status = $leave_type;
                    $att->time_in = $insertedDate;
                    $att->time_out = $insertedDate;
                    $att->undertime = '00:00:00';
                    $att->overtime = '00:00:00';
                    $att->hours_worked = '00:00:00';
                    $att->late = '00:00:00';

                    $att->save();
                }

            } elseif ($leavasdasde_type = "maternity leave") {
                while ($start_maternity_date <= $end_maternity_date) {

                    Attendance::where('time_status','!=',$leave_type)
                    ->where('status','!=','on leave')
                    ->whereDate('time_in',date("Y-m-d", strtotime($start_maternity_date)))
                    ->delete();

                    $att = new Attendance();
                    $att->employee_id = $employee_id;
                    $att->status = 'on leave';
                    $att->time_status = $leave_type;
                    $att->time_in = $start_maternity_date;
                    $att->time_out = $start_maternity_date;
                    $att->undertime = '00:00:00';
                    $att->overtime = '00:00:00';
                    $att->hours_worked = '00:00:00';
                    $att->late = '00:00:00';
                    $att->save();

                    $start_maternity_date = date(
                        'Y-m-d',
                        strtotime('+1 day', strtotime($start_maternity_date))
                    );
                }
            }

            $currentYear = date('Y');

            $sent_to_user = DB::table('users')
                ->join('employees', 'employees.employee_id', '=', 'users.id')
                ->select('employees.firstname', 'users.email')
                ->where('users.id', '=', $employee_id)
                ->first();

            //script for sending emails
            //$this->mail($sent_to_user->email,$sent_to_user->firstname,$currentYear,'approved',$request->input('id'));
            //end

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator' .
                    $e->getMessage()
            );
        }

        return back()
            ->with('success', 'Leave Request Approved')
            ->with('leaves', $leaves);
    }

    public function decline(Request $request)
    {
        //

        DB::beginTransaction();
        try {
            $leaves = DB::table('users')
                ->join('leaves', 'leaves.employee_id', '=', 'users.id')
                ->select(
                    'leaves.leave_id',
                    'users.name',
                    'leaves.leave_type',
                    'leaves.details',
                    'leaves.created_at',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.note'
                )
                ->where('leaves.archive', '=', 0)
                ->orderBy('leaves.leave_id', 'desc')
                ->paginate(10);

            $id = $request->input('id');
            $curDate = $request->input('curDate');

            $vl = $request->input('remaining_vl');
            $sl = $request->input('remaining_sl');
            $slp = $request->input('remaining_slp');
            $total = $vl + $sl + $slp;

            $reason = $request->input('decline_reason');

            $note = $vl . "," . $sl . "," . $slp . "," . $total . "," . $reason;

            $Leave = Leaves::find($id);
            $Leave->note = $note;
            $Leave->status = "Disapproved by HR";
            $Leave->date_approved = $curDate;
            $Leave->save();

            $leave_type = $Leave->leave_type;
            $inclusive_dates = $Leave->inclusive_dates;
            $employee_id = $Leave->employee_id;
            $start_maternity_date = date(
                'Y-m-d',
                strtotime($Leave->start_date)
            );
            $end_maternity_date = date('Y-m-d', strtotime($Leave->end_date));

            //delete rows
            if ($leave_type != "maternity leave") {
                $inclusive_dates = explode(',', $inclusive_dates);

                for ($i = 0; $i <= sizeof($inclusive_dates) - 1; $i++) {
                    $insertedDate = date(
                        'Y-m-d',
                        strtotime($inclusive_dates[$i])
                    );

                    DB::table('attendances')
                        ->where('employee_id', $employee_id)
                        ->where('status', 'on leave')
                        ->where('time_status', $leave_type)
                        ->whereDate('time_in', $insertedDate)
                        ->delete();
                }
            } elseif ($leavasdasde_type = "maternity leave") {
                while ($start_maternity_date <= $end_maternity_date) {
                    DB::table('attendances')
                        ->where('employee_id', $employee_id)
                        ->where('status', 'on leave')
                        ->where('time_status', $leave_type)
                        ->whereDate('time_in', $start_maternity_date)
                        ->delete();

                    $start_maternity_date = date(
                        'Y-m-d',
                        strtotime('+1 day', strtotime($start_maternity_date))
                    );
                }
            }

            $currentYear = date('Y');

            $sent_to_user = DB::table('users')
                ->join('employees', 'employees.employee_id', '=', 'users.id')
                ->select('employees.firstname', 'users.email')
                ->where('users.id', '=', $employee_id)
                ->first();

            //script for sending emails
            //$this->mail($sent_to_user->email,$sent_to_user->firstname,$currentYear,'disapproved',$request->input('id'));
            //end

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error
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

        return back()->with('error', 'Leave Request Declined');
        //->with('leaves', $leaves);
    }

    public function exportWord($id)
    {
        $today = date("Y-m-d H:i:s");
        // $leaves=leaves::where('leave_id',$id)->get();

        $leaves = DB::table('leaves')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'leaves.employee_id'
            )
            ->select(
                'employees.firstname',
                'employees.lastname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.inclusive_dates',
                'leaves.no_days',
                'leaves.leave_type',
                'leaves.details',
                'leaves.commutation',
                'leaves.supervisor_id',
                'leaves.approver_id',
                'leaves.signatory_id',
                'leaves.created_at'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get()
            ->first();

        $supervisor = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->supervisor_id)
            ->get()
            ->first();

        $hr = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->approver_id)
            ->get()
            ->first();

        $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->signatory_id)
            ->get()
            ->first();

        //define checkboxes
        $r_checkbox = "[ ] Requested";
        $nr_checkbox = "[ ] Not Requested";

        $vacation_checkbox = "[ ] Vacation";
        $v_employment_checkbox = "[ ] To seek employment";
        $v_others_checkbox = "[ ] Others (Specify) ___________";
        $v_country_checkbox = "[ ] In the country";
        $v_abroad_checkbox = "[ ] Abroad (Specify) ___________";

        $slp_checkbox = "[ ] Special Leave Program";

        $sick_checkbox = "[ ] Sick";
        $in_hospital_checkbox = "[ ] In hospital (Specify) _________";
        $out_patient_checkbox = "[ ] Out Patient (Specify) _________";

        $maternity_checkbox = "[ ] Maternity";
        $others_checkbox = "[ ] Others (Specify) ____________";

        if ($leaves->leave_type == "vacation leave") {
            $vacation_checkbox = "[x] Vacation";

            if (strpos($leaves->details, 'To seek employment') !== false) {
                $v_employment_checkbox = "[x] To seek employment";
            } else {
                $temp = explode("-", $leaves->details);
                $temp_details = explode("/", $temp[0]);
                $reason = $temp_details[1];
                $v_others_checkbox = "[x] Others (Specify) " . $reason;
            }

            if (strpos($leaves->details, 'Abroad (Specify)') !== false) {
                $temp = explode("-", $leaves->details);
                $temp_details = explode("/", $temp[1]);
                $reason = $temp_details[1];
                $v_abroad_checkbox = "[x] Abroad (Specify) " . $reason;
            } else {
                $v_country_checkbox = "[x] In the country";
            }
        } elseif ($leaves->leave_type == "special leave privilege") {
            $slp_checkbox = "[x] Special Leave Program";
        } elseif ($leaves->leave_type == "sick leave") {
            $sick_checkbox = "[x] Sick";

            if (strpos($leaves->details, 'In hospital (Specify)') !== false) {
                $temp = explode("/", $leaves->details);
                $reason = $temp[1];
                $in_hospital_checkbox = "[x] In hospital (Specify) " . $reason;
            } else {
                $temp = explode("/", $leaves->details);
                $reason = $temp[1];
                $out_patient_checkbox = "[x] Out Patient (Specify) " . $reason;
            }
        } elseif ($leaves->leave_type == "maternity leave") {
            $maternity_checkbox = "[x] Maternity";
        } elseif ($leaves->leave_type == "others") {
            $others_checkbox = "[ ] Others (Specify) " . $leaves->details;
        }

        if ($leaves->commutation == "Requested") {
            $r_checkbox = "[x] Requested";
            $nr_checkbox = "[ ] Not Requested";
        } elseif ($leaves->commutation == "Not Requested") {
            $r_checkbox = "[ ] Requested";
            $nr_checkbox = "[x] Not Requested";
        }

        $templateProcessor = new TemplateProcessor(
            'word-template/leaveapplicationform.docx'
        );
        $templateProcessor->setValue(
            'fullname',
            strtoupper($leaves->lastname) .
                ', ' .
                strtoupper($leaves->firstname) .
                ' ' .
                substr(strtoupper($leaves->middlename), 0, 1).'.'
        );
        $templateProcessor->setValue(
            'date_filing',
            date('d F Y', strtotime($leaves->created_at))
        );
        $templateProcessor->setValue('sg', $leaves->sg);
        $templateProcessor->setValue('position', $leaves->position);
        $templateProcessor->setValue('no_days', $leaves->no_days);
        $templateProcessor->setValue(
            'inclusive_dates',
            $leaves->inclusive_dates
        );

        //details of application
        $templateProcessor->setValue('vacation_checkbox', $vacation_checkbox);
        $templateProcessor->setValue(
            'v_employment_checkbox',
            $v_employment_checkbox
        );
        $templateProcessor->setValue('v_others_checkbox', $v_others_checkbox);
        $templateProcessor->setValue('slp_checkbox', $slp_checkbox);
        $templateProcessor->setValue('sick_checkbox', $sick_checkbox);
        $templateProcessor->setValue('maternity_checkbox', $maternity_checkbox);
        $templateProcessor->setValue('others_checkbox', $others_checkbox);
        $templateProcessor->setValue('v_country_checkbox', $v_country_checkbox);
        $templateProcessor->setValue('v_abroad_checkbox', $v_abroad_checkbox);
        $templateProcessor->setValue(
            'in_hospital_checkbox',
            $in_hospital_checkbox
        );
        $templateProcessor->setValue(
            'out_patient_checkbox',
            $out_patient_checkbox
        );

        $templateProcessor->setValue('r_checkbox', $r_checkbox);
        $templateProcessor->setValue('nr_checkbox', $nr_checkbox);

        //Set supervisor name start
        $supervisor_name="";
        if($supervisor->middlename!=""){
            $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".substr(strtoupper($supervisor->middlename), 0, 1).". ".$supervisor->lastname." ".$supervisor->extname;
        }
        else
        {
            $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".$supervisor->lastname." ".$supervisor->extname;
        }
        $templateProcessor->setValue('supervisor_name',strtoupper($supervisor_name));
        //Set supervisor name end

        //Set HR name start
        $hr_name="";
        if($hr->middlename!=""){
            $hr_name=$hr->prefix." ".$hr->firstname." ".substr(strtoupper($hr->middlename), 0, 1).". ".$hr->lastname." ".$hr->extname;
        }
        else{
            $hr_name=$hr->prefix." ".$hr->firstname." ".$hr->lastname." ".$hr->extname;
        }
        $templateProcessor->setValue('hr_name',strtoupper($hr_name));
        //Set HR name end

        //Set signatory name start
        $signatory_name="";
        if($signatory->middlename!=""){
            $signatory_name=$signatory->prefix." ".$signatory->firstname." ".substr($signatory->middlename, 0, 1).". ".$signatory->lastname." ".$signatory->extname;
        }
        else
        {
            $signatory_name=$signatory->prefix." ".$signatory->firstname." ".$signatory->lastname." ".$signatory->extname;
        }
        $templateProcessor->setValue('signatory_name',strtoupper($signatory_name));
        //Set signatory name end

        $templateProcessor->setValue('supervisor_position',$supervisor->position);
        $templateProcessor->setValue('hr_position', $hr->position);
        $templateProcessor->setValue('signatory_position',$signatory->position);

        $filename =
            $leaves->firstname . ' ' . $leaves->lastname . ' Leave Form';
        $templateProcessor->saveAs($filename . '.docx');
        return response()
            ->download($filename . '.docx')
            ->deleteFileAfterSend(true);
    }


    public function exportWordCto($id)
    {
        $today = date("Y-m-d H:i:s");
        // $leaves=leaves::where('leave_id',$id)->get();

        $leaves = DB::table('leaves')
            ->join(
                'employees',
                'employees.employee_id',
                '=',
                'leaves.employee_id'
            )
            ->select(
                'employees.firstname',
                'employees.lastname',
                'employees.middlename',
                'employees.position',
                'employees.sg',
                'leaves.inclusive_dates',
                'leaves.no_days',
                'leaves.leave_type',
                'leaves.details',
                'leaves.commutation',
                'leaves.supervisor_id',
                'leaves.approver_id',
                'leaves.signatory_id',
                'leaves.created_at',
                'leaves.updated_at'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get()
            ->first();

        $supervisor = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->supervisor_id)
            ->get()
            ->first();

        $hr = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->approver_id)
            ->get()
            ->first();

        $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->signatory_id)
            ->get()
            ->first();

        $templateProcessor = new TemplateProcessor(
            'word-template/ilcform.docx'
        );

        $templateProcessor->setValue('name',strtoupper($leaves->lastname) .', ' .strtoupper($leaves->firstname) .' ' . substr(strtoupper($leaves->middlename), 0, 1).'.');

        $templateProcessor->setValue(
            'date_filed',
            date('d F Y', strtotime($leaves->created_at))
        );

        $templateProcessor->setValue(
            'date_cert',
            date('d F Y', strtotime($leaves->updated_at))
        );

        $templateProcessor->setValue('designation', $leaves->position);
        $templateProcessor->setValue('no_days', $leaves->no_days);
        $templateProcessor->setValue(
            'inclusive_dates',
            $leaves->inclusive_dates
        );

        //details of application

        //Set supervisor name start
        $supervisor_name="";
        if($supervisor->middlename!=""){
            $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".substr(strtoupper($supervisor->middlename), 0, 1).". ".$supervisor->lastname." ".$supervisor->extname;
        }
        else
        {
            $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".$supervisor->lastname." ".$supervisor->extname;
        }
        $templateProcessor->setValue('supervisor_name',strtoupper($supervisor_name));
        //Set supervisor name end

        //Set HR name start
        $hr_name="";
        if($hr->middlename!=""){
            $hr_name=$hr->prefix." ".$hr->firstname." ".substr(strtoupper($hr->middlename), 0, 1).". ".$hr->lastname." ".$hr->extname;
        }
        else
        {
            $hr_name=$hr->prefix." ".$hr->firstname." ".$hr->lastname." ".$hr->extname;
        }
        $templateProcessor->setValue('hr_name',strtoupper($hr_name));
        //Set HR name end

        //Set signatory name start
        $signatory_name="";
        if($signatory->middlename!=""){
            $signatory_name=$signatory->prefix." ".$signatory->firstname." ".substr($signatory->middlename, 0, 1).". ".$signatory->lastname." ".$signatory->extname;
        }
        else
        {
            $signatory_name=$signatory->prefix." ".$signatory->firstname." ".$signatory->lastname." ".$signatory->extname;
        }
        $templateProcessor->setValue('signatory_name',strtoupper($signatory_name));
        //Set signatory name end

        //set positions
        $templateProcessor->setValue('supervisor_position',$supervisor->position);
        $templateProcessor->setValue('hr_position', $hr->position);
        $templateProcessor->setValue('signatory_position',$signatory->position);

        //set file name
        $filename = $leaves->firstname . ' ' . $leaves->lastname . ' Leave Form';

        //export to wordfile
        $templateProcessor->saveAs($filename . '.docx');
        return response()
            ->download($filename . '.docx')
            ->deleteFileAfterSend(true);
    }



public function archivedLeave()
    {
        DB::beginTransaction();
        try {
            $leaves = DB::table('users')
                ->join('leaves', 'leaves.employee_id', '=', 'users.id')
                ->select(
                    'leaves.leave_id',
                    'users.name',
                    'leaves.leave_type',
                    'leaves.details',
                    'leaves.created_at',
                    'leaves.start_date',
                    'leaves.end_date',
                    'leaves.status',
                    'leaves.note'
                )
                ->where('leaves.archive', '=', 1)
                ->orderBy('leaves.leave_id', 'desc')
                ->paginate(10);
            //->get();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            //redirect back with error
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

        return view('admin.leaves.archived')->with('leaves', $leaves);
    }

    public function archive(Request $request)
    {
        $id = $request->input('leave_id');
        $Leave = Leaves::find($id);
        $Leave->archive = 1;
        $Leave->save();
        return back();
    }

    public function restore(Request $request)
    {
        $id = $request->input('leave_id');
        $Leave = Leaves::find($id);
        $Leave->archive = 0;
        $Leave->save();
        return back();
    }


    public function mail($email,$name,$currentYear,$leave_status,$leave_id)
    {
            $to_name = $name;
            $to_email = $email;
            $data = [
                'email' => $to_email,
                'name' => $to_name,
                'year' => $currentYear,
                'id' => $leave_id,
                'leave_status' => $leave_status,
            ];
            Mail::send('leaveemail', $data, function ($message) use (
                $to_name,$to_email) {
                $message
                    ->to($to_email, $to_name)
                    ->subject('Leave Application Status');
                $message->from('noreply@ils.dole.gov.ph', 'TALMS');
            });
        
    }

    
}
