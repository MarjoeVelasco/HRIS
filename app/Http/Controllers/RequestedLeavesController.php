<?php

namespace App\Http\Controllers;
use App\FiledLeaves;
use App\FiledCto;
use App\LeaveCredits;
use App\CtoCredits;
use App\Employee;
use App\User;
use App\ErrorLog;
use DB;
use App;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class RequestedLeavesController extends Controller
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
        //
        $leaves = FiledLeaves::where('employee_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->where('isdisabled', '=', 0)
            ->paginate(20);
        
        $leaves_cto = FiledCto::where('employee_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->where('isdisabled', '=', 0)
            ->paginate(20);

        $leave_credits = DB::table('leave_credits')
        ->join(
            'filed_leaves',
            'leave_credits.id',
            '=',
            'filed_leaves.credits_id'
        )
        ->select(
            'leave_credits.balance_vl',
            'leave_credits.balance_sl',
            'leave_credits.id',
            'leave_credits.created_at',
            'leave_credits.date_certification',

        )
        ->where('filed_leaves.employee_id', '=', auth()->user()->id)
        ->orderby('created_at', 'desc')
        ->first();

        $cto_credits = DB::table('cto_credits')
        ->join(
            'filed_cto',
            'cto_credits.id',
            '=',
            'filed_cto.credits_id'
        )
        ->select(
            'cto_credits.date_certification',
            'cto_credits.hours_earned',
            'cto_credits.id',
            'cto_credits.created_at',

        )
        ->where('filed_cto.employee_id', '=', auth()->user()->id)
        ->orderby('created_at', 'desc')
        ->first();

        $initial_credits = DB::table('leave_cards')
        ->select('total_vl','total_sl','hours_earned')
        ->where('user_id', '=', auth()->user()->id)
        ->first();        


        $balance_vl ="0.000";
        $balance_sl ="0.000";
        $date_certification ="";
        
        $certification_as_of="";
        $hours_earned="00:00:00";

        if($leave_credits!=null)
        {
            $balance_vl = $leave_credits->balance_vl;
            $balance_sl = $leave_credits->balance_sl;
            $date_certification = $leave_credits->date_certification;
        }

        else if($leave_credits==null)
        {
            if($initial_credits!=null)
            {
                $balance_vl = $initial_credits->total_vl;
                $balance_sl = $initial_credits->total_sl;
                $date_certification = "November 11, 2022";
            }
         
        }



        if($cto_credits!=null)
        {
            $certification_as_of = $cto_credits->date_certification;
            $hours_earned = $cto_credits->hours_earned;
        }

        else if($cto_credits==null)
        {
            if($initial_credits!=null)
            {
                $hours_earned = $initial_credits->hours_earned;
                $certification_as_of = "November 11, 2022";
            }   
        }


        return view('users.requestedleaves',compact('leaves','leaves_cto','balance_vl','balance_sl','date_certification','certification_as_of','hours_earned'));
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

    public function export($id)
    {
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
                'employees.sg',
                'employees.division',
                'employees.signature',
                'filed_leaves.remarks',
                'filed_leaves.supervisor_id',
                'filed_leaves.hr_id',
                'filed_leaves.supervisor_remarks',
                'filed_leaves.hr_remarks',
                'filed_leaves.signatory_remarks',
                'filed_leaves.sub_signatory_remarks',
                'filed_leaves.sub_signatory_id',
                'filed_leaves.signatory_id',
                'filed_leaves.credits_id',
                'filed_leaves.created_at',
                'filed_leaves.inclusive_dates',
                'filed_leaves.no_days',
                'filed_leaves.commutation',
                'filed_leaves.leave_type',
                'filed_leaves.leave_attributes',
                'filed_leaves.leave_remarks',
                'filed_leaves.is_external',
                'filed_leaves.external_name',
                'filed_leaves.external_designation',
            )
            ->where('filed_leaves.id', '=', $id)
            ->get();

        $leave_details = "";
        $vl =" ";
        $vl_specify =" ";

        $mfl =" ";
        $sl = " ";
        $ml = " ";
        $pl = " ";
        $slp = " ";
        $slp2 = " ";
        $sl2 = " ";
        $vawcl = " ";
        $rl = " ";
        $slbw = " ";
        $sel = " ";
        $al = " ";
        $ol = "";
        $ol_details = "";
        $ol_specify = "";

        $total_vl = "";
        $total_sl = "";
        $less_vl = "";
        $less_sl = "";
        $balance_vl = "";
        $balance_sl = "";
        $date_certification = "";


        $leave_type="";
        $inclusive_dates="";
        $division = " ";
        $employee_name="";
        $employee_lastname="";
        $supervisor_name="";
        $supervisor_position="";
        $hr_name="";
        $hr_position="";
        $signatory_name=" ";
        $signatory_position="";

        $hr_signature="";
        $supervisor_signature="";

        $sub_signatory="";
        $sub_signatory_signature="";
       

        foreach ($leaves as $leave) {
            $division = $leave->division;
            $leave_type = $leave->leave_type;
            $employee_lastname=$leave->lastname;
            $inclusive_dates=$leave->inclusive_dates;
            //set leave type
            if($leave_type=="vacation leave"){
                $vl ="X";
                $leave_details=json_decode($leave->leave_attributes)->leave_details;
            }
            else if($leave_type=="mandatory forced leave"){
                $mfl ="X";
            }
            else if($leave_type=="sick leave"){
                $sl ="X";
                $leave_details=json_decode($leave->leave_attributes)->leave_details;
            }
            else if($leave_type=="maternity leave"){
                $ml ="X";
            }
            else if($leave_type=="paternity leave"){
                $pl ="X";
            }
            else if($leave_type=="special privilege leave"){
                $slp ="X";
            }
            else if($leave_type=="solo parent leave"){
                $slp2 ="X";
            }
            else if($leave_type=="study leave"){
                $sl2 ="X";
                $leave_details=json_decode($leave->leave_attributes)->leave_details;
            }
            else if($leave_type=="vawc leave"){
                $vawcl ="X";
            }
            else if($leave_type=="rehabilitation leave"){
                $rl ="X";
            }
            else if($leave_type=="special leave benefits for women"){
                $slbw ="X";
            }
            else if($leave_type=="special emergency (calamity) leave"){
                $sel ="X";
            }
            else if($leave_type=="adoption leave"){
                $al ="X";
            }
            else if($leave_type=="others"){
                $ol ="X";
                $ol_specify=json_decode($leave->leave_attributes)->other_details_input;
                $ol_details = json_decode($leave->leave_attributes)->other_leave_details;
            }

            //set name*******************************************************************
            if($leave->middlename!=""){
                $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
            }
            else{
                $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
            }

            //get supervisor name
            $supervisor = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature',
            )
            ->where('employee_id', '=', $leave->supervisor_id)
            ->first();
            
            $supervisor_signature=$supervisor->signature;

            if($supervisor->middlename!=""){
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".substr(strtoupper($supervisor->middlename), 0, 1).". ".$supervisor->lastname." ".$supervisor->extname;
            }
            else{
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".$supervisor->lastname." ".$supervisor->extname;
            }
            $supervisor_position=$supervisor->position;

            //get HR name
            $hr = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature',
            )
            ->where('employee_id', '=', $leave->hr_id)
            ->first();

            $hr_signature=$hr->signature;

            if($hr->middlename!=""){
                $hr_name=$hr->prefix." ".$hr->firstname." ".substr(strtoupper($hr->middlename), 0, 1).". ".$hr->lastname." ".$hr->extname;
            }
            else{
                $hr_name=$hr->prefix." ".$hr->firstname." ".$hr->lastname." ".$hr->extname;
            }
            $hr_position=$hr->position;

            $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature',
                
            )
            ->where('employee_id', '=', $leave->signatory_id)
            ->first();

            $signatory_signature=$signatory->signature;

            $signatory_name="";
            if($signatory->middlename!=""){
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".substr(strtoupper($signatory->middlename), 0, 1).". ".$signatory->lastname." ".$signatory->extname;
            }
            else{
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".$signatory->lastname." ".$signatory->extname;
            }
            $signatory_position=$signatory->position;

            //get sub signatory
            if($leave->sub_signatory_id!=null)
            {
                $sub_signatory = DB::table('employees')
                ->select(
                    'signature',
                )
                ->where('employee_id', '=', $leave->sub_signatory_id)
                ->first();
    
                $sub_signatory_signature=$sub_signatory->signature;
            }

            if($leave->credits_id!=null)
            {
                $leave_credits = DB::table('leave_credits')
                ->select(
                   'total_vl','total_sl','less_vl','less_sl','balance_vl','balance_sl','date_certification'
                )
                ->where('id', '=', $leave->credits_id)
                ->first();

                $total_vl = $leave_credits->total_vl;
                $total_sl = $leave_credits->total_sl;
        
                $less_vl = $leave_credits->less_vl;
                $less_sl = $leave_credits->less_sl;
        
                $balance_vl = $leave_credits->balance_vl;
                $balance_sl = $leave_credits->balance_sl;
                $date_certification = $leave_credits->date_certification;

            }
        }

        
        $filename = $leave_type." application (".$employee_lastname.") (".$inclusive_dates.")";


        $temp = explode("(", $division);
        $division_short = str_replace(")", "", $temp[1]);


        $today = date("Y-m-d H:i:s");

        $pdf = PDF::loadView(
            'users.leavetemplate',
            compact(
                'leaves',
                'vl','mfl','sl','ml','pl','slp','slp2','sl2','vawcl','rl','slbw','sel','al','ol','ol_specify','ol_details',
                'leave_details',
                'division_short',
                'employee_name',
                'supervisor_name','supervisor_position',
                'hr_name','hr_position',
                'signatory_name','signatory_position',
                'total_vl','total_sl','less_vl','less_sl','balance_vl','balance_sl','date_certification',
                'hr_signature','supervisor_signature','signatory_signature',
                'sub_signatory_signature'
            )
        );
        
        
        return $pdf
        ->setPaper('legal', 'portrait')
        ->download($filename . ' leave.pdf');
        


    }

    public function exportCto($id)
    {
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
                'employees.signature',
                'filed_cto.remarks',
                'filed_cto.supervisor_id',
                'filed_cto.hr_id',
                'filed_cto.signatory_id',
                'filed_cto.hr_remarks',
                'filed_cto.supervisor_remarks',
                'filed_cto.signatory_remarks',
                'filed_cto.sub_signatory_remarks',
                'filed_cto.sub_signatory_id',
                'filed_cto.credits_id',
                'filed_cto.created_at',
                'filed_cto.inclusive_dates',
                'filed_cto.no_days',
                'filed_cto.is_external',
                'filed_cto.external_name',
                'filed_cto.external_designation',
            )
            ->where('filed_cto.id', '=', $id)
            ->get();

        $inclusive_dates="";

        $employee_name="";
        $employee_lastname="";
        $supervisor_name="";
        $supervisor_position="";
        $supervisor_signature="";
        $hr_name="";
        $hr_position="";
        $hr_signature="";
        $signatory_name=" ";
        $signatory_position="";
        $signatory_signature="";

        $sub_signatory="";
        $sub_signatory_signature="";        

        $certification_as_of="";
        $number_of_hours="";
        $last_certification="";

        foreach ($leaves as $leave) {
            $employee_lastname=$leave->lastname;
            $inclusive_dates=$leave->inclusive_dates;

            //set name*******************************************************************
            if($leave->middlename!=""){
                $employee_name=$leave->firstname." ".substr(strtoupper($leave->middlename), 0, 1).". ".$leave->lastname." ".$leave->extname;
            }
            else{
                $employee_name=$leave->firstname." ".$leave->lastname." ".$leave->extname;
            }

            //get supervisor name
            $supervisor = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature',
            )
            ->where('employee_id', '=', $leave->supervisor_id)
            ->first();

            $supervisor_signature=$supervisor->signature;

            if($supervisor->middlename!=""){
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".substr(strtoupper($supervisor->middlename), 0, 1).". ".$supervisor->lastname." ".$supervisor->extname;
            }
            else{
                $supervisor_name=$supervisor->prefix." ".$supervisor->firstname." ".$supervisor->lastname." ".$supervisor->extname;
            }
            $supervisor_position=$supervisor->position;

            //get HR name
            $hr = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature'
            )
            ->where('employee_id', '=', $leave->hr_id)
            ->first();

            $hr_signature=$hr->signature;

            if($hr->middlename!=""){
                $hr_name=$hr->prefix." ".$hr->firstname." ".substr(strtoupper($hr->middlename), 0, 1).". ".$hr->lastname." ".$hr->extname;
            }
            else{
                $hr_name=$hr->prefix." ".$hr->firstname." ".$hr->lastname." ".$hr->extname;
            }
            $hr_position=$hr->position;

            $signatory = DB::table('employees')
            ->select(
                'prefix',
                'firstname',
                'lastname',
                'middlename',
                'extname',
                'position',
                'signature',
            )
            ->where('employee_id', '=', $leave->signatory_id)
            ->first();

            $signatory_signature=$signatory->signature;

            $signatory_name="";
            if($signatory->middlename!=""){
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".substr(strtoupper($signatory->middlename), 0, 1).". ".$signatory->lastname." ".$signatory->extname;
            }
            else{
                $signatory_name=$signatory->prefix." ".$signatory->firstname." ".$signatory->lastname." ".$signatory->extname;
            }
            $signatory_position=$signatory->position;

            //get sub signatory
            if($leave->sub_signatory_id!=null)
            {
                $sub_signatory = DB::table('employees')
                ->select(
                    'signature',
                )
                ->where('employee_id', '=', $leave->sub_signatory_id)
                ->first();
    
                $sub_signatory_signature=$sub_signatory->signature;
            }

            if($leave->credits_id!=null)
            {
                $leave_credits = DB::table('cto_credits')
                ->select(
                   'date_certification','hours_earned','last_certification'
                )
                ->where('id', '=', $leave->credits_id)
                ->first();

                $certification_as_of=$leave_credits->date_certification;
                $number_of_hours=$leave_credits->hours_earned;
                $last_certification=$leave_credits->last_certification;

            }

        }

        $today = date("Y-m-d H:i:s");

        $filename = "ILC application (".$employee_lastname.") (".$inclusive_dates.")";

        $pdf = PDF::loadView(
            'users.ctotemplate',
            compact(
                'leaves',
                'employee_name',
                'supervisor_name','supervisor_position',
                'hr_name','hr_position',
                'signatory_name','signatory_position',
                'certification_as_of','number_of_hours','last_certification',
                'hr_signature','supervisor_signature','signatory_signature',
                'sub_signatory_signature'
            )
        );
        
        return $pdf
        ->setPaper('legal', 'portrait')
        ->download($filename . '.pdf');

    }

    public function cancelLeave(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $name = Employee::where('employee_id', auth()->user()->id)
                    ->join('users', 'employees.employee_id', '=', 'users.id')
                    ->select("employees.firstname","users.email")
                    ->first();

            $today = date("Y-m-d H:i:s");
            
            $reason = $request->input('cancel_reason');
        
            $leave = FiledLeaves::find($request->input('cancel_leave_id'));
            $leave->status = "Cancelled";
            $leave->remarks = $today." Request cancelled by ".$name->firstname." [".$name->email."] due to : ".$reason."\n".$leave->remarks;
            $leave->save();
                
            $leave_credit_id = $leave->credits_id;
            //dd($leave_credit_id);

            if($leave_credit_id!=null)
            {
                //revert leave credits

                LeaveCredits::where('id', $leave_credit_id)->delete();

                $leave = FiledLeaves::find($request->input('cancel_leave_id'));
                $leave->credits_id = null;
                $leave->save();

            }

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
            return back()->with(
                'error',
                'Execution Error. Record Not Saved! Please contact the administrator'
            );
        }
        
        return back()->with('success', 'Leave request has been cancelled');
    }

    public function cancelCto(Request $request)
    {
        DB::beginTransaction();
        try
        {

            $name = Employee::where('employee_id', auth()->user()->id)
                    ->join('users', 'employees.employee_id', '=', 'users.id')
                    ->select("employees.firstname","users.email")
                    ->first();

            $today = date("Y-m-d H:i:s");
            
            $reason = $request->input('cancel_reason');
        
            $leave = FiledCto::find($request->input('cancel_leavecto_id'));
            $leave->status = "Cancelled";
            $leave->remarks = $today." Request cancelled by ".$name->firstname." [".$name->email."] due to : ".$reason."\n".$leave->remarks;
            $leave->save();
                
            $leave_credit_id = $leave->credits_id;
            //dd($leave_credit_id);

            if($leave_credit_id!=null)
            {
                //revert cto credits

                CtoCredits::where('id', $leave_credit_id)->delete();

                $leave = FiledCto::find($request->input('cancel_leave_id'));
                $leave->credits_id = null;
                $leave->save();

            }

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
            'body' => 'This is to notify you that your CTO application has been cancelled by '.$decliner->name.' ['.$decliner->email.'] due to '.$reason.'. Kindly click the button below to download your leave.',
            'link' => 'download-cto/'.$leave->id,
            'btn_label' => 'Download CTO',
            ];

            $subject="CTO Application - Cancelled";
        
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

        return back()->with('success', 'CTO request has been cancelled');
    }



    public function archiveLeave($id)
    {
        DB::beginTransaction();
        try
        {
            $leave = FiledLeaves::find($id);
            $leave->isdisabled = 1;
            $leave->save();

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
        
        return back()->with('success', 'Leave archived');
    }

    public function view($id,$type)
    {

        $leaves = DB::table($type)
        ->select('remarks')
        ->where('id', '=', $id)
        ->get();

        return response()->json(['data' => $leaves]);
    }

    public function unlockDownload($type,$id)
    {
        DB::beginTransaction();
        try
        {
            if($type=="leaves")
            {
                $leave = FiledLeaves::find($id);
                $leave->is_rated = 1;
                $leave->save();
            }
            else if($type="cto")
            {
                $leave = FiledCto::find($id);
                $leave->is_rated = 1;
                $leave->save();
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
        
        return back()->with('success', 'Download unlocked');

    }

}
