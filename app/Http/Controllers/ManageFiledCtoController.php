<?php

namespace App\Http\Controllers;
use App\FiledCto;
use App\User;
use App\ErrorLog;
use DB;
use App;
use PDF;
use App\CtoCredits;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Crypt;

class ManageFiledCtoController extends Controller
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
        //

        $leaves = DB::table('users')
                ->join('filed_cto', 'filed_cto.employee_id', '=', 'users.id')
                ->select(
                    'filed_cto.id',
                    'users.name',
                    'filed_cto.leave_type',
                    'filed_cto.created_at',
                    'filed_cto.credits_id',
                    'filed_cto.hr_id',
                    'filed_cto.supervisor_id',
                    'filed_cto.signatory_id',
                    'filed_cto.status',
                    'filed_cto.attachment',
                )
                ->where('filed_cto.isarchived', '=', 0)
                ->orderBy('filed_cto.id', 'desc')
                ->paginate(10);

        return view('admin.filedcto.index')->with('leaves', $leaves);
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

    public function archiveCto($id)
    {

        $leave = FiledCto::find($id);
        $leave->isarchived = 1;
        $leave->save();

        return redirect('managefiledcto')->with('success', 'CTO archived');
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
            'filed_cto.created_at',
            'filed_cto.hr_id',
            'filed_cto.supervisor_id',
            'filed_cto.signatory_id',
            'filed_cto.inclusive_dates',
            'filed_cto.no_days',
            'filed_cto.leave_type',
            'filed_cto.reason',
            'filed_cto.attachment',
            'filed_cto.particulars',
        )
        ->where('filed_cto.id', '=', $id)
        ->get();

        foreach ($leaves as $leave) {

            //set name*******************************************************************
            if($leave->middlename!=""){
                $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
            }
            else{
                $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
            }

            if($leave->credits_id!=null)
            {
                $leave_credits = DB::table('cto_credits')
                ->select(
                    'date_certification','hours_earned','last_certification'
                )
                ->where('id', '=', $leave->credits_id)
                ->first();

                $date_certification = $leave_credits->date_certification;
                $hours_earned=$leave_credits->hours_earned;
                $last_certification = $leave_credits->last_certification;

            }

        }

        return view('admin.filedcto.certify',compact(
            'leaves',
            'employee_name',
            'date_certification','hours_earned','last_certification'
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

    public function reviewCto($id)
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

        foreach ($leaves as $leave) {

            //set name*******************************************************************
            if($leave->middlename!=""){
                $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
            }
            else{
                $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
            }

            if($leave->credits_id!=null)
            {
                $leave_credits = DB::table('cto_credits')
                ->select(
                    'date_certification','hours_earned','last_certification'
                )
                ->where('id', '=', $leave->credits_id)
                ->first();

                $date_certification = $leave_credits->date_certification;
                $hours_earned=$leave_credits->hours_earned;
                $last_certification = $leave_credits->last_certification;

            }

        }

        return view('admin.filedcto.review',compact(
            'leaves',
            'employee_name',
            'date_certification','hours_earned','last_certification'
        ));
        
    }

    public function routeCto($id)
    {
        DB::beginTransaction();
        try
        {
            $remarks = "";
            $email = "";
            $email_subject = "";
            
            $status = "";
            $mailData="";
    
            $date = date('Y-m-d H:i:s');
    
            $signatory = FiledCto::where('id',$id)
                        ->select('leave_type','hr_id','hr_remarks','supervisor_id','supervisor_remarks','sub_signatory_id','sub_signatory_remarks','signatory_id','signatory_remarks')
                        ->first();
            //check who's first
            //check if HR has remarks
            if($signatory->hr_remarks!="Approved")
            {
                //get HR ID, email and name
                $user=User::join('employees','employees.employee_id','=','users.id')
                        ->select('employees.firstname','employees.lastname','users.email','users.id','users.email')
                        ->where('users.id',$signatory->hr_id)
                        ->first();
    
    
                $remarks = $date." Request has been sent to HR signatory for approval\n";
                $status = "Routed to HR";
                $email_subject="For HR Approval";
                $email=$user->email;
    
                //build email body
                $mailData = [
                    'name' => 'Hi '.$user->firstname." ".$user->lastname,
                    'body' => 'This is to notify you that a user has filed a '.$signatory->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested CTO.',
                    'link' => 'review-cto/'.$id.'/'.$signatory->hr_id,
                    'btn_label' => 'Review Leave',
                ];
    
            }
    
            //check if supervisor has remarks
            else if($signatory->supervisor_remarks!="Approved")
            {
                //get supervisor ID, email and name
                $user=User::join('employees','employees.employee_id','=','users.id')
                ->select('employees.firstname','employees.lastname','users.email','users.id','users.email')
                ->where('users.id',$signatory->supervisor_id)
                ->first();
    
               $remarks = $date." Request has been routed to supervisor for approval\n";
               $status = "Routed to Supervisor";
               $email_subject="For Supervisory Approval";
               $email=$user->email;
               
    
               //build email body
               $mailData = [
                   'name' => 'Hi '.$user->firstname." ".$user->lastname,
                   'body' => 'This is to notify you that a user has filed a '.$signatory->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested CTO.',
                   'link' => 'review-cto-supervisor/'.$id.'/'.$signatory->supervisor_id,
                   'btn_label' => 'Review Leave',
               ];
    
            }
    
            //check if sub signatory has remarks
            else if($signatory->sub_signatory_remarks!="Approved" && $signatory->sub_signatory_id!=null)
            {
                //get sub signatory ID, email and name
                $user=User::join('employees','employees.employee_id','=','users.id')
                ->select('employees.firstname','employees.lastname','users.email','users.id','users.email')
                ->where('users.id',$signatory->sub_signatory_id)
                ->first();  
                
                $remarks = $date." Request has been routed to ED/DED for approval\n";
                $status = "Routed to DED";
                $email_subject="For Review and Approval";
                $email=$user->email;
    
                //build email body
               $mailData = [
                   'name' => 'Hi '.$user->firstname." ".$user->lastname,
                   'body' => 'This is to notify you that a user has filed a '.$signatory->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested CTO.',
                   'link' => 'signatory-review/cto/secondary/'.Crypt::encryptString($id).'/'.Crypt::encryptString($signatory->sub_signatory_id),
                   'btn_label' => 'Review Leave',
               ];
    
            }
    
            //check if signatory has remarks
            else if($signatory->signatory_remarks!="Approved")
            {
                //get sub signatory ID, email and name
                $user=User::join('employees','employees.employee_id','=','users.id')
                ->select('employees.firstname','employees.lastname','users.email','users.id','users.email')
                ->where('users.id',$signatory->signatory_id)
                ->first();   
    
                $remarks = $date." Request has been routed to ED/DED for approval\n";
                $status = "Routed to ED";
                $email_subject="For Review and Approval";
                $email=$user->email;
    
                //build email body
               $mailData = [
                   'name' => 'Hi '.$user->firstname." ".$user->lastname,
                   'body' => 'This is to notify you that a user has filed a '.$signatory->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested leave.',
                   'link' => 'signatory-review/cto/primary/'.Crypt::encryptString($id).'/'.Crypt::encryptString($signatory->signatory_id),
                   'btn_label' => 'Review Leave',
               ];
    
            }
    
            
    
            $leave = FiledCto::find($id);
            $leave->remarks = $remarks."".$leave->remarks;
            $leave->status = $status;
            $leave->save();
    
            $subject="CTO Application - ".$email_subject;
            
            Mail::to($email)->send(new SendMail($mailData,$subject));

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

    
        return redirect('managefiledcto')->with('success', 'Leave request routed for esign');
    }

    public function ctoApprove(Request $request)
    {

        DB::beginTransaction();
        try
        {
            $date = date('Y-m-d H:i:s');
                
            //insert to leavecredits
            $leave_credits = new CtoCredits;
            $leave_credits->hours_earned = $request->input('hours_earned');
            $leave_credits->last_certification = $request->input('last_certification');
            $leave_credits->date_certification = $request->input('certification_as_of');
            $leave_credits->save();

            $insertedLeaveCredits = $leave_credits->id;

            //get approver
            $certifier = User::where('id',auth()->user()->id)
            ->select('id','name','email')
            ->first(); 

            $leave = FiledCto::find($request->input('leave_id'));
            $leave->credits_id = $insertedLeaveCredits;
            $leave->remarks = $date." Request has been routed to supervisor for approval\n".$date." Request has been signed and approved by ".$certifier->name." [".$certifier->email."]\n".$leave->remarks;
            $leave->status = "Routed to Supervisor";
            $leave->hr_remarks = "Approved";
            $leave->particulars = $request->input('cto_particulars');
            $leave->save();

            //get supervisor ID, email and name
            $user=User::where('id',$leave->supervisor_id)
            ->select('id','name','email')
            ->first();
            
            //send notification to supervisor
            $mailData = [
            'name' => 'Hi '.$user->name,
            'body' => 'This is to notify you that a user has filed a '.$leave->leave_type.' and you are listed as one of its signatories. Kindly click the button below to review the requested CTO.',
            'link' => 'review-cto-supervisor/'.$leave->id.'/'.$user->id,
            'btn_label' => 'Review CTO',
            ];

            $subject="CTO Application - For Supervisory Approval";
        
            Mail::to($user->email)->send(new SendMail($mailData,$subject));

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

        return redirect('managefiledcto')->with('success', 'Leave has been approved');
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
    
            //get approver
            $certifier = User::where('id',auth()->user()->id)
            ->select('id','name','email')
            ->first(); 
    
            $leave = FiledCto::find($request->input('leave_id'));
            $leave->credits_id = null;
            $leave->remarks = $date." Request has been declined by ".$certifier->name." [".$certifier->email."] due to : ".$reason."\n".$leave->remarks;
            $leave->status = "Cancelled";
            $leave->hr_remarks = $reason;
            $leave->save();
    
            $leave_credit_id = $leave->credits_id;
    
            if($leave_credit_id!=null)
            {
                //revert leave credits
                LeaveCredits::where('id', $leave_credit_id)->delete();
                $leave = FiledLeaves::find($request->input('cancel_leave_id'));
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
             'body' => 'This is to notify you that your '.$leave->leave_type.' application has been declined by '.$decliner->name.' ('.$decliner->email.') due to '.$reason.'. Kindly click the button below to download your leave.',
             'link' => 'download-cto/'.$leave->id,
             'btn_label' => 'Download CTO',
             ];
    
             $subject="CTO Application - Declined";
         
             Mail::to($user->email)->send(new SendMail($mailData,$subject));

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

        return redirect('managefiledcto')->with('success', 'Leave has been disapproved');
    }

    public function approveOverride($id) {

        //dd(auth()->user()->email);
        $date = date('Y-m-d H:i:s');

        $leave = FiledCto::find($id);
        $leave->credits_id = null;
        $leave->remarks = $date." Request has been manually approved (override) by ".auth()->user()->name." [".auth()->user()->email."]\n".$leave->remarks;
        $leave->status = "Completed";
        $leave->signatory_remarks = "Approved";
        $leave->date_approved = $date;
        $leave->save();

        //get requestor
        $requestor = User::join('employees','employees.employee_id','=','users.id')
            ->select('employees.firstname','employees.lastname','users.email')
            ->where('users.id',$leave->employee_id)
            ->first();

        //set email subject    
        $subject=ucwords($leave->leave_type)." Application - Completed";
        
        //build mail data for applicant
        $mailData_applicant = [
            'name' => 'Hi '.$requestor->firstname." ".$requestor->lastname,
            'body' => 'Your request '.$leave->leave_type.' application has been approved. Kindly click the button below to access the completed agreement. Dont forget to answer the customer feedback form using the take the survey tab or visit bit.ly/ILSCSSInternalProcess.',
            'link' => 'myleaves',
            'btn_label' => 'Download Agreement',
            ];

        //send email to requestor
        Mail::to($requestor->email)->send(new SendMail($mailData_applicant,$subject));
        return redirect('managefiledleaves')->with('success', 'Leave has been approved');
    }

}
