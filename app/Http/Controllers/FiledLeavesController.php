<?php

namespace App\Http\Controllers;
use App\Leaves;
use App\FiledLeaves;
use App\FiledCto;
use App\Employee;
use App\ErrorLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
use App\Mail\SendMail;

class FiledLeavesController extends Controller
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
        $position_chief = "Division Chief";

        //get list of directors
        $director = User::whereHas("roles", function ($q) {
            $q->where("name", "Director");
            })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select('users.id','employees.firstname','employees.middlename','employees.lastname','employees.extname','employees.position')
            ->where('users.is_disabled','!=',1)
            ->get();

        //get list of chiefs
        $chiefs = User::whereHas("roles", function ($q) {
            $q->where("name", "Chief LEO/Supervisor");
            })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select('users.id','employees.firstname','employees.middlename','employees.lastname','employees.extname','employees.position')
            ->where('users.is_disabled','!=',1)
            ->get();

        //get list of HRs
        $hr = User::whereHas("roles", function ($q) {
            $q->where("name", "HR/FAD");
            })
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select('users.id','employees.firstname','employees.middlename','employees.lastname','employees.extname','employees.position')
            ->where('users.is_disabled','!=',1)
            ->get();

            /*
        $leaves = filedleaves::where('employee_id', auth()->user()->id)
            ->where('id', 3)
            ->get();

        $test = filedleaves::where('employee_id', auth()->user()->id)
            ->where('id', 3)
            ->select('vacation_slp_details')
            ->first();
        
            */
        //to decode
        //dd(json_decode($test->vacation_slp_details)->slp_vacay_details_input);
     

        return view('users.fileleave')
            ->with('users', $chiefs)
            ->with('hr', $hr)
            ->with('director', $director);
            //->with('leaves', $leaves);
            
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
        DB::beginTransaction();
        try
        {
            //get if external is checked
            $external_approver_checker = $request->input('is_external_checkbox');
            //accept only specific file formats
            $this->validate($request, [
                'attachment' => 'mimes:jpeg,png,jpg,pdf,docx,doc|max:5120',
            ]);
            
            $sub_approver = User::role('Sub approver')->select('id')->first();
            $reason = $request->get('reason_late');

            if($reason=="")
            {
                $reason="n/a";
            }
        
            $data="";
            //get leave type
            $leave_type = $request->get('leave_type');
            $inclusive_dates="";
            $start_date="";
            $end_date="";
            $no_days="";
            $insertedID="";
            $leave_link="";

            if($leave_type=="cto")
            {
                $inclusive_dates = $request->input('inclusive_dates_cto');
                $date_collection = explode(",", $inclusive_dates);
                $start_date = current($date_collection);
                $end_date = end($date_collection);
                $no_days = $request->get('hours_days_cto');
                $details="whole day";
            }

            else
            {
                //if monetization
                $data = $request->get('other_leave_details');

                if($data=="monetization_leave")
                {
                    $inclusive_dates="N/A";  
                    $start_date = "N/A";
                    $end_date = "N/A";
                    $no_days = $request->get('no_days_monetization');  
                    
                }

                else
                {
                    //get inclusive dates
                    $inclusive_dates = $request->input('inclusive_dates');
                    $date_collection = explode(",", $inclusive_dates);
                    $start_date = current($date_collection);
                    $end_date = end($date_collection);
                    $no_days = count($date_collection);
                }

            }


            if($leave_type=="special privilege leave"||$leave_type=="vacation leave"){
                $data = $request->only('leave_type','leave_details','slp_vacay_details_input');
            }

            else if($leave_type=="sick leave"){
                $data = $request->only('leave_type','leave_details','sick_details_input');
            }

            else if($leave_type=="study leave"){
                $data = $request->only('leave_type','leave_details');
            }

            else if($leave_type=="special leave benefits for women"){
                $data = $request->only('leave_type','slbfw_details_input');
            }

            else if($leave_type=="others"){
                $data = $request->only('leave_type','other_leave_details','other_details_input');
            }

            else if($leave_type=="cto"){
                if (strpos($no_days, '.') !== false) {
                    $data = $request->only('leave_type','date_half_day','time_day_half_cto');
                }

                else{
                    $data = $request->only('leave_type');
                }
            }

            else{
                $data = $request->only('leave_type');
            }

            $leave_attributes = json_encode($data);

            //dd($leave_attributes);
            $date = date('Y-m-d H:i:s');

            $leave_table="";

            if($leave_type=="cto")
            {
             
                //set table name for attachment
                $leave_table="filed_cto";

                //insert to filedleaves
                $leave = new FiledCto;
                $leave->employee_id = $request->input('employee_id');
                $leave->supervisor_id = $request->get('supervisor_id');
                $leave->hr_id = $request->get('approver_id');
                $leave->signatory_id = $request->get('signatory_id');
                $leave->leave_type = $request->get('leave_type');
                $leave->leave_details = $leave_attributes;
                $leave->inclusive_dates = $inclusive_dates;
                $leave->no_days = $no_days;
                $leave->start_date = $start_date;
                $leave->end_date = $end_date;
                $leave->status = "Pending";
                $leave->remarks = $date." CTO request sent to HR";
                $leave->reason = $reason;
                $leave->save();

                $insertedID=$leave->id;
                $supervisor = $leave->supervisor_id;
                $signatory = $leave->signatory_id;
                $leave_link="certify-cto";


                //check if external
                if($external_approver_checker === "1") {
                    $checker = FiledCto::find($leave->id);
                    $checker->sub_signatory_id = $request->get('signatory_id');
                    $checker->sub_signatory_remarks = "waiting";
                    $checker->is_external = true;
                    $checker->external_name = $request->get('external_name');
                    $checker->external_designation = $request->get('external_designation');
                    $checker->save();
                }

                else {
                    //if DED is not chosen as supervisor AND signatory
                    if($supervisor != $sub_approver->id && $signatory != $sub_approver->id)
                    {
                        $checker = FiledCto::find($leave->id);
                        $checker->sub_signatory_id = $sub_approver->id;
                        $checker->sub_signatory_remarks = "waiting";
                        $checker->save();
                    }
                }
            }
            else
            {
                //set table name for attachment
                $leave_table="filed_leaves";

                //insert to filedleaves
                $leave = new FiledLeaves;
                $leave->employee_id = $request->input('employee_id');
                $leave->supervisor_id = $request->get('supervisor_id');
                $leave->hr_id = $request->get('approver_id');
                $leave->signatory_id = $request->get('signatory_id');
                $leave->leave_type = $request->get('leave_type');
                $leave->leave_attributes = $leave_attributes;
                $leave->inclusive_dates = $inclusive_dates;
                $leave->no_days = $no_days;
                $leave->start_date = $start_date;
                $leave->end_date = $end_date;
                $leave->commutation = $request->get('commutation');
                $leave->status = "Pending";
                $leave->remarks = $date." Leave request sent to HR";
                $leave->reason = $reason;
                $leave->internal_notes = $request->input('internal_notes');
                $leave->save();

                $insertedID=$leave->id;
                $supervisor = $leave->supervisor_id;
                $signatory = $leave->signatory_id;
                $leave_link="certify-leave";

                //check if external
                if($external_approver_checker === "1") {
                    $checker = FiledLeaves::find($leave->id);
                    $checker->sub_signatory_id = $request->get('signatory_id');
                    $checker->sub_signatory_remarks = "waiting";
                    $checker->is_external = true;
                    $checker->external_name = $request->get('external_name');
                    $checker->external_designation = $request->get('external_designation');
                    $checker->save();
                }
                else
                {
                    //if DED is not chosen as supervisor AND signatory
                    if($supervisor != $sub_approver->id && $signatory != $sub_approver->id)
                    {
                        $checker = FiledLeaves::find($leave->id);
                        $checker->sub_signatory_id = $sub_approver->id;
                        $checker->sub_signatory_remarks = "waiting";
                        $checker->save();
                    }
                }
                
            }

            if ($request->file('attachment') !== null)
            {
                
                    $temp_file = $request->file('attachment');
                    //rename file
                    $attachment = $leave_table.'_'.$insertedID.'.'.$temp_file->getClientOriginalExtension();
                    //move to public folder
                    $temp_file->move(public_path('images/attachments'), $attachment);
                    //update table
                    DB::update('update '.$leave_table.' set attachment = ? where id = ?', [
                        'images/attachments/'.$attachment,
                        $insertedID,
                    ]);
                

            }

            $newLink = $leave_link."/".$insertedID;

            //get user details
            $employee_name=Employee::where('employee_id',Auth::user()->id)
            ->select("firstname","lastname")
            ->first();

            

            $subject="Leave Application - For Certitication";

            //get list of HRs
            $hr = User::whereHas("roles", function ($q) {
            $q->where("name", "HR/FAD");
            })
            ->select('users.email','users.name')
            ->where('users.is_disabled','!=',1)
            ->get();

            //mail to all HR
            foreach ($hr as $user) {

                $mailData = [
                    'name' => 'Good day '.$user->name,
                    'body' => 'This is to notify you that '.$employee_name->firstname.' '.$employee_name->lastname.' has filed a '.$leave_type.'. Kindly disregard this if you have already performed the necessary actions, otherwise click the button below to review the requested leave.',
                    'link' => $newLink,
                    'btn_label' => 'Review Leave',
                ];
               
                    //Mail::to($user->email)->cc(Auth::user()->email)->send(new SendMail($mailData,$subject));

            }  

            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            $log = new ErrorLog;
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();;
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            return back()->with('error', 'Execution Error. Record Not Saved! Please contact the administrator');
        }
        
        return redirect('myleaves')->with('success', 'Leave application submitted ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
