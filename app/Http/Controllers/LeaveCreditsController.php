<?php

namespace App\Http\Controllers;
use App\Leaves;
use App\FiledLeaves;
use App\LeaveCredits;
use App\Employee;
use App\ErrorLog;
use App\User;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Mail\SendMail;

use Illuminate\Http\Request;

class LeaveCreditsController extends Controller
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
            $date = date('Y-m-d H:i:s');
            
            //insert to filedleaves
            $leave_credits = new LeaveCredits;
            $leave_credits->total_vl = $request->input('total_earned_vl');
            $leave_credits->total_sl = $request->input('total_earned_sl');
            $leave_credits->less_vl = $request->input('less_vl');
            $leave_credits->less_sl = $request->input('less_sl');
            $leave_credits->balance_vl = $request->input('balance_vl');
            $leave_credits->balance_sl = $request->input('balance_sl');
            $leave_credits->date_certification = $request->input('date_certification');
            $leave_credits->save();

            $insertedLeaveCredits = $leave_credits->id;

            //get approver
            $certifier = User::where('id',auth()->user()->id)
                        ->select('id','name','email')
                        ->first(); 

            $leave = FiledLeaves::find($request->input('leave_id'));
            $leave->credits_id = $insertedLeaveCredits;
            $leave->remarks = $date." Request has been certified by ".$certifier->name." [".$certifier->email."]\n".$leave->remarks;
            $leave->status = "Processing";
            $leave->save();

            $user=User::where('id',$leave->employee_id)
            ->select('name','email')
            ->first();

            //get employee id of leave request
            $mailData = [
                'name' => $user->name,
                'body' => 'This is to notify you that your '.$leave->leave_type.' request is now being processed. Click the button below to download the requested leave.',
                'link' => 'download-leave/'.$request->input('leave_id'),
                'btn_label' => 'Download Leave',
            ];

            $subject="Leave Application - Processing";
            
            //mail to user that email is now being processed
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
            
        return back()->with('success', 'Leave credits has been certified');

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
