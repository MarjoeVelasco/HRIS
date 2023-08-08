<?php

namespace App\Http\Controllers;
use App\FiledCto;
use App\User;
use App\CtoCredits;
use DB;
use App;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\ErrorLog;

use Illuminate\Http\Request;

class CtoCreditsController extends Controller
{
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
            //get date
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
            $leave->remarks = $date." Request has been certified by ".$certifier->name." [".$certifier->email."]\n".$leave->remarks;
            $leave->status = "Processing";
            $leave->particulars = $request->input('cto_particulars');
            $leave->save();
            
            
            $user=User::where('id',$leave->employee_id)
                        ->select('name','email')
                        ->first();

            //get employee id of leave request
            $mailData = [
                    'name' => $user->name,
                    'body' => 'This is to notify you that your '.$leave->leave_type.' request is now being processed. Click the button below to download the requested leave.',
                    'link' => 'download-cto/'.$request->input('leave_id'),
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
