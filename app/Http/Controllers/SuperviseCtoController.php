<?php

namespace App\Http\Controllers;
use App\FiledLeaves;
use App\FiledCto;
use App\LeaveCredits;
use App\CtoCredits;
use App\Employee;
use App\User;
use DB;
use App\ErrorLog;
use App;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Crypt;

class SuperviseCtoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin|Division Chief']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
        //$this->middleware(['isSupervisor']); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $leaves = FiledCto::where('supervisor_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->where('isdisabled', '=', 0)
            ->paginate(10);

        //
        return view('supervisor.generalcto',compact('leaves'));
    }

    public function showPrimary($encrypted_leave_id,$encrypted_user_id)
    {
                //decrypt leave id and user_id
                $leave_id="";
                $user_id="";
                $user_role="primary";
        
                try {
                    $leave_id = Crypt::decryptString($encrypted_leave_id);
                    $user_id = Crypt::decryptString($encrypted_user_id);
                } catch (DecryptException $e) {
                    //error in decryption route to not found
                    return abort(404);
                }
        
                //check if right user on requested leave that has not been approved/disapproved yet
                if (FiledCto::where('id', $leave_id)->where('signatory_id',$user_id)
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
                $leaves = DB::table('filed_cto')
                ->join(
                    'employees',
                    'employees.employee_id',
                    '=',
                    'filed_cto.employee_id'
                )
                ->select(
                    'employees.firstname',
                    'employees.lastname',
                    'employees.middlename',
                    'employees.extname',
                    'employees.position',
                    'employees.division',
                    'filed_cto.id',
                    'filed_cto.credits_id',
                    'filed_cto.hr_id',
                    'filed_cto.created_at',
                    'filed_cto.leave_attributes',
                    'filed_cto.inclusive_dates',
                    'filed_cto.no_days',
                    'filed_cto.leave_type',
                )
                ->where('filed_cto.id', '=', $leave_id)
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

                try {
                    $leave_id = Crypt::decryptString($encrypted_leave_id);
                    $user_id = Crypt::decryptString($encrypted_user_id);
                } catch (DecryptException $e) {
                    //error in decryption route to not found
                    return abort(404);
                }
        
                //check if right user on requested leave that has not been approved/disapproved yet
                if (FiledCto::where('id', $leave_id)->where('sub_signatory_id',$user_id)
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
                $leaves = DB::table('filed_cto')
                ->join(
                    'employees',
                    'employees.employee_id',
                    '=',
                    'filed_cto.employee_id'
                )
                ->select(
                    'employees.firstname',
                    'employees.lastname',
                    'employees.middlename',
                    'employees.extname',
                    'employees.position',
                    'employees.division',
                    'filed_cto.id',
                    'filed_cto.credits_id',
                    'filed_cto.hr_id',
                    'filed_cto.created_at',
                    'filed_cto.leave_attributes',
                    'filed_cto.inclusive_dates',
                    'filed_cto.no_days',
                    'filed_cto.leave_type',
                )
                ->where('filed_cto.id', '=', $leave_id)
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
                    'encrypted_user_id',
                    'encrypted_leave_id',
                    'leave_id','user_id',
                    'leave_type','employee_name'));
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

    public function reviewCTO($id)
    {
        $date_certification = date("F d, Y");
        $hours_earned="00:00:00";
        $last_certification = " ";

        $leaves = DB::table('filed_cto')
        ->join(
            'employees',
            'employees.employee_id',
            '=',
            'filed_cto.employee_id'
        )
        ->select(
            'employees.firstname',
            'employees.lastname',
            'employees.middlename',
            'employees.extname',
            'employees.position',
            'employees.division',
            'filed_cto.id',
            'filed_cto.credits_id',
            'filed_cto.hr_id',
            'filed_cto.supervisor_id',
            'filed_cto.signatory_id',
            'filed_cto.created_at',
            'filed_cto.inclusive_dates',
            'filed_cto.no_days',
            'filed_cto.leave_type',
            'filed_cto.reason',
            'filed_cto.attachment',
            'filed_cto.particulars',
        )
        ->where('filed_cto.id', '=', $id)
        ->get();

        $leave_type="";
        $division = " ";
        $employee_name="";

        foreach ($leaves as $leave) {
            $division = $leave->division;
            $leave_type = $leave->leave_type;

             //set name*******************************************************************
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

        return view('supervisor.reviewcto',compact(
            'leaves',
            'employee_name',
            'leave_type',
        ));
    }

    public function ctoDecline(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $date = date('Y-m-d H:i:s');
            $reason = $request->input('reason');

            if($reason=="")
            {
                $reason="Reason for disapproval unspecified";
            }

            $leave = FiledCto::find($request->input('leave_id'));
            $leave->credits_id = null;
            $leave->remarks = $date." Request has been declined your supervisor due to : ".$reason."\n".$leave->remarks;
            $leave->status = "Cancelled";
            $leave->supervisor_remarks = $reason;
            $leave->save();

            $leave_credit_id = $leave->credits_id;

            if($leave_credit_id!=null)
            {
                //revert leave credits
                LeaveCredits::where('id', $leave_credit_id)->delete();
                $leave = FiledLeaves::find($request->input('leave_id'));
                $leave->credits_id = null;
                $leave->save();

            }

            //send mail to user that leave has been declined
            //get decliner
            $decliner=User::where('id',auth()->user()->id)
            ->select('id','name','email')
            ->first();

            //get user employee
            $user=User::where('id',$leave->employee_id)
            ->select('id','name','email')
            ->first();
            
            //send notification to user
            $mailData = [
            'name' => 'Hi '.$user->name,
            'body' => 'This is to notify you that your '.$leave->leave_type.' application has been declined by '.$decliner->name.' ('.$decliner->email.') due to "'.$reason.'". Kindly click the button below to download your leave.',
            'link' => 'download-leave/'.$leave->id,
            'btn_label' => 'Download Leave',
            ];

            $subject="CTO Application - Declined";
        
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
        
        
        return redirect('supervise-general-cto')->with('success', 'Leave has been disapproved');
    }


    public function approveCto($id)
    {
        DB::beginTransaction();
        try
        {

            $date = date('Y-m-d H:i:s'); 
            //insert to filedleaves

            //check if sub approver is set for initials
            $sub_approver_flag=$this->checkSubApprover($id);
            $leave_status_remarks="Routed to DED";
         
            if($sub_approver_flag===false)
            {
                $leave_status="Routed to ED";
            }

            $leave = FiledCto::find($id);
            $leave->remarks = $date." Request has been routed to ED/DED for approval\n".$date." Request has been signed and approved by your Supervisor\n".$leave->remarks;
            $leave->status = $leave_status_remarks;
            $leave->supervisor_remarks = "Approved";
            $leave->save();

            $user="";
            $user_link="";

            if($sub_approver_flag===false)
            {
                //send mail to signatory
                //get HR ID, email and name
                $user=User::where('id',$leave->signatory_id)
                ->select('id','name','email')
                ->first();

                $user_link="primary";
            }

            else
            {
                //send mail to sub_signatory
                //get HR ID, email and name
                $user=User::where('id',$leave->sub_signatory_id)
                ->select('id','name','email')
                ->first();

                $user_link="secondary";
            }

            //send notification to user
            $mailData = [
            'name' => 'Hi '.$user->name,
            'body' => 'This is to notify you that a user has filed a '.$leave->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested leave.',
            'link' => 'signatory-review/cto/'.$user_link.'/'.Crypt::encryptString($leave->id).'/'.Crypt::encryptString($user->id),
            'btn_label' => 'Review and Sign',
            ];

            $subject="CTO Application - For Review and Approval";
        
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

        return redirect('supervise-general-cto')->with('success', 'Leave has been disapproved');
    }

    public function checkSubApprover($id)
    {
        $status=true;

        $leave = FiledCto::where('id', $id)
          ->select('sub_signatory_id')
          ->first(); 

        //if sub signatory is null
        if(is_null($leave->sub_signatory_id)) 
        {
            $status=false;
        }

        return $status;
    }
}
