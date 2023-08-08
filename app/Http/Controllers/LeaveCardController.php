<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\FiledLeaves;
use App\FiledCto;
use App\LeaveCredits;
use App\LeaveCard;
use App\CtoCredits;
use App\Employee;
use App\User;
use App\ErrorLog;
use DB;
use App;
use PDF;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class LeaveCardController extends Controller
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
        //select all employees
        $employees = DB::table('users')
            ->join('employees', 'employees.employee_id', '=', 'users.id')
            ->select(
                'users.id',
                'employees.firstname',
                'employees.lastname'
            )
            ->where('is_disabled',0)
			->orderby('employees.firstname','ASC')
            ->get();

        $leave_card = DB::table('leave_cards')
            ->join('employees', 'employees.employee_id', '=', 'user_id')
            ->select(
                'employees.firstname',
                'employees.lastname',
                'leave_cards.total_vl',
                'leave_cards.total_sl',
                'leave_cards.hours_earned',
            )
            ->get();

        return view('admin.leavecard.index')
                ->with('employees',$employees)
                ->with('leave_cards',$leave_card);
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
            LeaveCard::create([
            'user_id' => $request->get('user_id'),
            'total_vl' => $request->input('enter_vl'),
            'total_sl' => $request->input('enter_sl'),
            'hours_earned' => $request->input('enter_cto'),
        ]);

        DB::commit();
        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();

            return back()
            ->with('error','Execution Error. Record Not Saved! Please contact the administrator');
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveCard  $leaveCard
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveCard $leaveCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveCard  $leaveCard
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveCard $leaveCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveCard  $leaveCard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveCard $leaveCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveCard  $leaveCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveCard $leaveCard)
    {
        //
    }
}
