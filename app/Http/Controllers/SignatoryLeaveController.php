<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use App\FiledLeaves;
use App\FiledCto;
use App\LeaveCredits;
use App\CtoCredits;
use App\ErrorLog;
use App\Employee;
use App\User;
use DB;
use App;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


class SignatoryLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


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
    public function showPrimary($encrypted_leave_id,$encrypted_user_id)
    {
                //decrypt leave id and user_id
                $leave_id="";
                $user_id="";
                $user_role="primary";
                $type="leave";
        
                try {
                    $leave_id = Crypt::decryptString($encrypted_leave_id);
                    $user_id = Crypt::decryptString($encrypted_user_id);
                } catch (DecryptException $e) {
                    //error in decryption route to not found
                    return abort(404);
                }
        
                //check if right user on requested leave that has not been approved/disapproved yet
                if (FiledLeaves::where('id', $leave_id)->where('signatory_id',$user_id)
                ->where('status','!=',"Cancelled")
                ->where('isarchived',0)
                ->where('isdisabled',0)
                ->where('hr_remarks','Approved')
                ->where('supervisor_remarks','Approved')
                ->where('signatory_remarks','waiting')
                ->exists()) {
                // ...
                }
        
                else{
                    return abort(404);
                }
        
        
                //get employee name of requestor
                $leaves = DB::table('filed_leaves')
                ->join(
                    'employees',
                    'employees.employee_id',
                    '=',
                    'filed_leaves.employee_id'
                )
                ->select(
                    'employees.firstname',
                    'employees.lastname',
                    'employees.middlename',
                    'employees.extname',
                    'employees.position',
                    'employees.division',
                    'filed_leaves.id',
                    'filed_leaves.credits_id',
                    'filed_leaves.hr_id',
                    'filed_leaves.created_at',
                    'filed_leaves.leave_attributes',
                    'filed_leaves.inclusive_dates',
                    'filed_leaves.no_days',
                    'filed_leaves.leave_type',
                    'filed_leaves.reason',
                    'filed_leaves.attachment',
                )
                ->where('filed_leaves.id', '=', $leave_id)
                ->get();

                $leave_type="";
                $employee_name="";

                foreach ($leaves as $leave) {

                    $leave_type = $leave->leave_type;
                    if($leave->middlename!=""){
                        $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
                    }
                    else{
                        $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
                    }
        
                    if($leave_type=="others"){
                        $leave_type = $leave_type . " (".json_decode($leave->leave_attributes)->other_details_input.")";
                    }

                }
        
                return view('leavesignatory',compact(
                    'leaves',
                    'user_role',
                    'type',
                    'leave_id','user_id',
                    'encrypted_user_id',
                    'encrypted_leave_id',
                    'leave_type','employee_name'));
    }

    public function showSecondary($encrypted_leave_id,$encrypted_user_id)
    {
                //decrypt leave id and user_id
                $leave_id="";
                $user_id="";
                $user_role="secondary";
                $type="leave";

                try {
                    $leave_id = Crypt::decryptString($encrypted_leave_id);
                    $user_id = Crypt::decryptString($encrypted_user_id);
                } catch (DecryptException $e) {
                    //error in decryption route to not found
                    return abort(404);
                }
        
                //check if right user on requested leave that has not been approved/disapproved yet
                if (FiledLeaves::where('id', $leave_id)->where('sub_signatory_id',$user_id)
                    ->where('status','!=',"Cancelled")
                    ->where('isarchived',0)
                    ->where('isdisabled',0)
                    ->where('hr_remarks','Approved')
                    ->where('supervisor_remarks','Approved')
                    ->where('sub_signatory_remarks','waiting')
                    ->exists()) {
                    // ...
                }
        
                else{
                    return abort(404);
                }
        
        
                //get employee name of requestor
                $leaves = DB::table('filed_leaves')
                ->join(
                    'employees',
                    'employees.employee_id',
                    '=',
                    'filed_leaves.employee_id'
                )
                ->select(
                    'employees.firstname',
                    'employees.lastname',
                    'employees.middlename',
                    'employees.extname',
                    'employees.position',
                    'employees.division',
                    'filed_leaves.id',
                    'filed_leaves.credits_id',
                    'filed_leaves.hr_id',
                    'filed_leaves.created_at',
                    'filed_leaves.leave_attributes',
                    'filed_leaves.inclusive_dates',
                    'filed_leaves.no_days',
                    'filed_leaves.leave_type',
                    'filed_leaves.reason',
                    'filed_leaves.attachment',
                )
                ->where('filed_leaves.id', '=', $leave_id)
                ->get();

                $leave_type="";
                $employee_name="";

                foreach ($leaves as $leave) {

                    $leave_type = $leave->leave_type;
                    if($leave->middlename!=""){
                        $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
                    }
                    else{
                        $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
                    }
        
                    if($leave_type=="others"){
                        $leave_type = $leave_type . " (".json_decode($leave->leave_attributes)->other_details_input.")";
                    }

                }
        
                return view('leavesignatory',compact(
                    'leaves',
                    'user_role',
                    'type',
                    'encrypted_user_id',
                    'encrypted_leave_id',
                    'leave_id','user_id',
                    'leave_type','employee_name'));
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
    public function disapproveLeave(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $encrypted_user_id=$request->input('encrypted_user_id_input');
            $encrypted_leave_id=$request->input('encrypted_leave_id_input');
            $type="leave";

            $leave_id="";
            $user_id="";
            $today = date('Y-m-d H:i:s');

            //decrypt parameters
            try {
                $leave_id = Crypt::decryptString($encrypted_leave_id);
                $user_id = Crypt::decryptString($encrypted_user_id);
            } catch (DecryptException $e) {
                //error in decryption route to not found
                return abort(404);
            }

            $reason = $request->input('reason');

            //get disapprover
            $name = Employee::where('employee_id', $user_id)
            ->join('users', 'employees.employee_id', '=', 'users.id')
            ->select("employees.firstname","users.email")
            ->first();

            $leave = FiledLeaves::find($leave_id);
            $leave->status = "Cancelled";
            $leave->remarks = $today." Request cancelled by ".$name->firstname." [".$name->email."] due to : ".$reason."\n".$leave->remarks;
            $leave->save();

            //get leave credits
            $leave_credit_id = $leave->credits_id;

            if($leave_credit_id!=null)
            {
                //revert leave credits
                LeaveCredits::where('id', $leave_credit_id)->delete();
                $leave = FiledLeaves::find($leave_id);
                $leave->credits_id = null;
                $leave->save();
            }

            //get decliner
            $decliner=User::where('id',$user_id)
            ->select('id','name','email')
            ->first();

            //get user employee
            $user=User::where('id',$leave->employee_id)
            ->select('id','name','email')
            ->first();
            
            //send notification to user
            $mailData = [
            'name' => 'Hi '.$user->name,
            'body' => 'This is to notify you that your '.$leave->leave_type.' application has been cancelled by '.$decliner->name.' ['.$decliner->email.'] due to '.$reason.'. Kindly click the button below to download your leave.',
            'link' => 'download-leave/'.$leave->id,
            'btn_label' => 'Download Leave',
            ];

            $subject=ucwords($leave->leave_type)." Application - Cancelled";
        
            Mail::to($user->email)->send(new SendMail($mailData,$subject));

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
            return back()->with('error','Execution Error. Record Not Saved! Please contact the administrator'
            );
        }

        return view('responserecorded',compact('encrypted_leave_id','type'));
    }

    public function approvePrimary($encrypted_user_id,$encrypted_leave_id)
    {
        DB::beginTransaction();
        try
        {
            $leave_id="";
            $user_id="";
            $date = date('Y-m-d H:i:s'); 
            $leave_status_remarks="Completed";
            $type="leave";

            //decrypt parameters
            try {
                $leave_id = Crypt::decryptString($encrypted_leave_id);
                $user_id = Crypt::decryptString($encrypted_user_id);
            } catch (DecryptException $e) {
                //error in decryption route to not found
                return abort(404);
            }

            //check if right user on requested leave that has not been approved/disapproved yet
            if (FiledLeaves::where('id', $leave_id)->where('signatory_id',$user_id)
                ->where('status','!=',"Cancelled")
                ->where('isarchived',0)
                ->where('isdisabled',0)
                ->where('hr_remarks','Approved')
                ->where('supervisor_remarks','Approved')
                ->where('signatory_remarks','waiting')
                ->exists()) {
                // ...
            }

            else{
                return abort(404);
            }

            //get approver
            $certifier = User::where('id',$user_id)
            ->select('id','name','email')
            ->first();
            
            //update leave
            $leave = FiledLeaves::find($leave_id);
            $leave->remarks = $date." Request cycle has been completed.\n".$date." Completed Agreement has been sent to all parties.\n".$date." Request has been signed and approved by ".$certifier->name." [".$certifier->email."]\n".$leave->remarks;
            $leave->status = $leave_status_remarks;
            $leave->signatory_remarks = "Approved";
            $leave->date_approved = $date;
            $leave->save();


            //get requestor
            $requestor = User::join('employees','employees.employee_id','=','users.id')
            ->select('employees.firstname','employees.lastname','users.email')
            ->where('users.id',$leave->employee_id)
            ->first();

            //get supervisor
            $supervisor = User::where('id',$leave->supervisor_id)
            ->select('email')
            ->first();

            //get hr
            $hr = User::where('id',$leave->hr_id)
            ->select('email')
            ->first();

            //get hr
            $signatory = User::where('id',$leave->signatory_id)
            ->select('email')
            ->first();


            $email_party = array();

            if(is_null($leave->sub_signatory_id)) 
            {
                array_push($email_party, $supervisor->email, $signatory->email);
            }
            else
            {
                //get hr
                $sub_signatory = User::where('id',$leave->sub_signatory_id)
                ->select('email')
                ->first();

                array_push($email_party, $supervisor->email, $sub_signatory->email, $signatory->email);
            }


            //recode sending of emails to all involved recipients

            $mailData = [
                'name' => 'Hi',
                'body' => 'The request '.$leave->leave_type.' application requested by '.$requestor->firstname.' '.$requestor->lastname.' has been completed. Kindly click the button below to download the completed agreement.',
                'link' => 'download/leave/'.Crypt::encryptString($leave->id),
                'btn_label' => 'Download Agreement',
                ];
            
            //set email subject    
            $subject=ucwords($leave->leave_type)." Application - Completed";
    
            //send mail
            Mail::to($hr->email)->cc($email_party)->send(new SendMail($mailData,$subject));


            //build mail data for applicant
            $mailData_applicant = [
                'name' => 'Hi '.$requestor->firstname." ".$requestor->lastname,
                'body' => 'Your request '.$leave->leave_type.' application has been approved. Kindly click the button below to access the completed agreement. Dont forget to answer the customer feedback form using the take the survey tab or visit bit.ly/ILSCSSInternalProcess.',
                'link' => 'myleaves',
                'btn_label' => 'Download Agreement',
                ];

            //send email to requestor
            Mail::to($requestor->email)->send(new SendMail($mailData_applicant,$subject));


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
            return back()->with('error','Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        
        return view('responserecorded',compact('encrypted_leave_id','type'));
    }


    public function approveSecondary($encrypted_user_id,$encrypted_leave_id)
    {

        DB::beginTransaction();
        try
        {
            $leave_id="";
            $user_id="";
            $date = date('Y-m-d H:i:s'); 
            $leave_status_remarks="Routed to ED";
            $type="leave";
            $leave_details = "";

            //decrypt parameters
            try {
                $leave_id = Crypt::decryptString($encrypted_leave_id);
                $user_id = Crypt::decryptString($encrypted_user_id);
            } catch (DecryptException $e) {
                //error in decryption route to not found
                return abort(404);
            }

            //check if right user on requested leave that has not been approved/disapproved yet
            if (FiledLeaves::where('id', $leave_id)->where('sub_signatory_id',$user_id)
                ->where('status','!=',"Cancelled")
                ->where('isarchived',0)
                ->where('isdisabled',0)
                ->where('hr_remarks','Approved')
                ->where('supervisor_remarks','Approved')
                ->where('sub_signatory_remarks','waiting')
                ->exists()) {
                // ...
                //get leave details
                $leave_details = FiledLeaves::where('id', $leave_id)->select('is_external')->first();
                //dd($leave_details);
            }

            else{
                return abort(404);
            }

            if($leave_details->is_external==1) {
                $leave_status_remarks="Routed to External";
            }
            //get approver
            $certifier = User::where('id',$user_id)
            ->select('id','name','email')
            ->first(); 

            //update leave
            $leave = FiledLeaves::find($leave_id);
            $leave->remarks = $date." Request has been signed and approved by ".$certifier->name." [".$certifier->email."]\n".$leave->remarks;
            $leave->status = $leave_status_remarks;
            $leave->sub_signatory_remarks = "Approved";
            $leave->save();

            //if leave is routed for external signatory
            if($leave_details->is_external==1) {

                $subject="Leave Application - For external routing";
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
                        'body' => 'This is to notify you that '.$leave->leave_type.' no. '.$leave->id.' has been signed and is now ready for external routing. Kindly click the button below to download.',
                        'link' => 'download/leave/'.Crypt::encryptString($leave->id),
                        'btn_label' => 'Review Leave',
                    ];
                    Mail::to($user->email)->send(new SendMail($mailData,$subject));
                }  
            }

            else {
                $user=User::where('id',$leave->signatory_id)
                ->select('id','name','email')
                ->first();
                //collate mail data
                $mailData = [
                    'name' => 'Hi '.$user->name,
                    'body' => 'This is to notify you that a user has filed a '.$leave->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested leave.',
                    'link' => 'signatory-review/leave/primary/'.Crypt::encryptString($leave->id).'/'.Crypt::encryptString($user->id),
                    'btn_label' => 'Review and Sign',
                    ];
                //set email subject    
                $subject=ucwords($leave->leave_type)." Application - For Review and Approval";
                //send mail
                Mail::to($user->email)->send(new SendMail($mailData,$subject));
            }
           




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
            return back()->with('error','Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        
        return view('responserecorded',compact('encrypted_leave_id','type'));
    }


}
