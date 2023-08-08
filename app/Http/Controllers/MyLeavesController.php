<?php
namespace App\Http\Controllers;
use App\Leaves;
use Illuminate\Http\Request;
use App;
use PDF;
use DB;
class MyLeavesController extends Controller
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
        $leaves = leaves::where('employee_id', auth()->user()->id)
            ->where('archive', 0)
            ->orderby('leave_id', 'desc')
            ->paginate(10);
        return view('users.myleaves')->with('leaves', $leaves);
    }
    public function approve()
    {
        $leaves = leaves::where('employee_id', auth()->user()->id)
            ->orderby('leave_id', 'desc')
            ->paginate(10);
        return view('users.myleaves')->with('leaves', $leaves);
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
    public function archiveleave(Request $request)
    {
        //move leave to archive folder
        $leaves = Leaves::find($request->get('delete_leave_id'));
        $leaves->archive = 1;
        $leaves->save();
        return redirect('myleaves');
    }
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
        return view('users.viewleave')
            ->with('leaves', $leaves)
            ->with('supervisors', $supervisors);
    }

    public function showCTO($id)
    {

        $today = date("Y-m-d H:i:s");
        
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


            return view('users.viewleavecto',compact(
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
    public function export($id)
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
                'leaves.status',
                'leaves.note',
                'leaves.supervisor_id',
                'leaves.approver_id',
                'leaves.signatory_id',
                'leaves.created_at'
            )
            ->where('leaves.leave_id', '=', $id)
            ->get();
        $supervisor_id = "";
        $hr_id = "";
        $signatory_id = "";
        $leave_details = "";
        $leave_type = "";
        $leave_status = "";
        $leave_note = "";
        foreach ($leaves as $leave) {
            $supervisor_id = $leave->supervisor_id;
            $hr_id = $leave->approver_id;
            $signatory_id = $leave->signatory_id;
            $leave_details = $leave->details;
            $leave_type = $leave->leave_type;
            $leave_status = $leave->status;
            $leave_note = $leave->note;
        }
        $vacation_others = "";
        $vacation_abroad = "";
        $sick_in = "";
        $sick_out = "";
        if ($leave_type == "vacation leave") {
            if (strpos($leave_details, 'Others (Specify)') !== false) {
                $temp = explode("-", $leave_details);
                $temp_details = explode("/", $temp[0]);
                $vacation_others = $temp_details[1];
            }
            if (strpos($leave_details, 'Abroad (Specify)') !== false) {
                $temp = explode("-", $leave_details);
                $temp_details = explode("/", $temp[1]);
                $vacation_abroad = $temp_details[1];
            }
        }
        if ($leave_type == "sick leave") {
            if (strpos($leave_details, 'In hospital (Specify)') !== false) {
                $temp = explode("/", $leave_details);
                $sick_in = $temp[1];
            }
            if (strpos($leave_details, 'Out patient (Specify)') !== false) {
                $temp = explode("/", $leave_details);
                $sick_out = $temp[1];
            }
        }
        $vacation_leave_days = "";
        $sick_leave_days = "";
        $slp_days = "";
        $total_days = "";
        $days_w_pay = "";
        $days_wo_pay = "";
        $others_pay = "";
        $disapproved_reasons = "";
        if ($leave_status == "Approved by HR") {
            $temp = explode(",", $leave_note);
            $vacation_leave_days = $temp[0];
            $sick_leave_days = $temp[1];
            $slp_days = $temp[2];
            $total_days = $temp[3];
            $days_w_pay = $temp[4];
            $days_wo_pay = $temp[5];
            $others_pay = $temp[6];
        }
        if ($leave_status == "Disapproved by HR") {
            $temp = explode(",", $leave_note);
            $vacation_leave_days = $temp[0];
            $sick_leave_days = $temp[1];
            $slp_days = $temp[2];
            $total_days = $temp[3];
            $disapproved_reasons = $temp[4];
        }
        $supervisor = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $supervisor_id)
            ->get();
        $hr = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $hr_id)
            ->get();
        $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $signatory_id)
            ->get();
            
        $pdf = PDF::loadView(
            'users.exportleave',
            compact(
                'leaves',
                'supervisor',
                'hr',
                'signatory',
                'vacation_others',
                'vacation_abroad',
                'sick_in',
                'sick_out',
                'vacation_leave_days',
                'sick_leave_days',
                'slp_days',
                'total_days',
                'days_w_pay',
                'days_wo_pay',
                'others_pay',
                'disapproved_reasons'
            )
        );
        return $pdf
            ->setPaper('legal', 'portrait')
            ->download($today . ' leave.pdf');
    }


    public function exportcto($id)
    {
        $today = date("Y-m-d H:i:s");
        
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
            $name=strtoupper($leaves->lastname)." ".strtoupper($leaves->extname).", ".strtoupper($leaves->firstname)." ".substr(strtoupper($leaves->middlename), 0, 1).".";
            
            
            
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
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position'
            )
            ->where('employee_id', '=', $leaves->supervisor_id)
            ->first();

            //Set supervisor name start
            $supervisor_name="";
            if($supervisor->middlename!=""){
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".substr(strtoupper($supervisor->middlename), 0, 1).". ".$supervisor->lastname." ".$supervisor->extname;
            }
            else
            {
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".$supervisor->lastname." ".$supervisor->extname;
            }
            //Set supervisor name end
            $supervisor_position=$supervisor->position;

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
            ->first();

            //Set HR name start
            $hr_name="";
            if($hr->middlename!=""){
                $hr_name=$hr->prefix." ".$hr->firstname." ".substr(strtoupper($hr->middlename), 0, 1).". ".$hr->lastname." ".$hr->extname;
            }
            else{
                $hr_name=$hr->prefix." ".$hr->firstname." ".$hr->lastname." ".$hr->extname;
            }
            //Set HR name end
            $hr_position=$hr->position;


            $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
            )
            ->where('employee_id', '=', $leaves->signatory_id)
            ->first();

            $signatory_name="";
            if($signatory->middlename!=""){
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".substr(strtoupper($signatory->middlename), 0, 1).". ".$signatory->lastname." ".$signatory->extname;
            }
            else{
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".$signatory->lastname." ".$signatory->extname;
            }
            $signatory_position=$signatory->position;


        $pdf = PDF::loadView(
            'users.exportleavecto',
            compact(
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

            )
        );
        return $pdf
            ->setPaper('legal', 'portrait')
            ->download($today . ' leave.pdf');
    }













    public function allHolidays()
    {
        $holiday = DB::table('holidays')
            ->select('inclusive_dates')
            ->get();
        return response()->json(['data' => $holiday]);
    }

    //if holiday or weekend
    public function checker ($date = null, $id = null)
    {
        $check_weekend = date('l', strtotime($date));
        //convert to lowercase
        $converted = strtolower($check_weekend);
        if ($converted == "saturday" || $converted == "sunday") {
            //day is a weekend
            return response()->json(['data' => false]);
        } 
        
        else {
            
            $holidays = DB::table('holidays')
            ->select('id')
            ->where('inclusive_dates', 'like', '%' . $date . '%')
            ->get();
            //check if holiday
            if ($holidays->isNotEmpty()) {
                //day is holiday
                return response()->json(['data' => false]);
            } 
            
            else {

                $obaos = DB::table('obaos')
                ->select('id')
                ->where('employee_id',$id)
                ->where('inclusive_dates',$date)
                ->get();

                if ($obaos->isNotEmpty()) {
                    //day is holiday
                    return response()->json(['data' => false]);
                }
                
                else
                {
                    return response()->json(['data' => true]);
                }

               
            }
        }

    }
}
