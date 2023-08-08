<?php

namespace App\Http\Controllers;

use App\ObaoAttendances;

use App\Attendance;
use App\Other_attendances;
use App\Employee;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\ErrorLog;

use Illuminate\Http\Request;

class ObaoAttendancesController extends Controller
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
        $attendance = DB::table('users')
        ->join('obao_attendances', 'obao_attendances.employee_id', '=', 'users.id')
        ->select(
            'users.name',
            'users.id',
            'obao_attendances.attendance_id',
            'obao_attendances.time_in',
            'obao_attendances.status',
            'obao_attendances.time_status'
        )
        ->orderBy('obao_attendances.time_in', 'DESC')
        ->paginate(20);

        $oed = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where(
            'employees.division',
            'Office of the Executive Director (OED)'
        )
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)
        ->get();
    $erd = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where('employees.division', 'Employment Research Division (ERD)')
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)
        ->get();
    $lssrd = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where(
            'employees.division',
            'Labor and Social Relations Research Division (LSRRD)'
        )
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)
        ->get();
    $wwrd = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where(
            'employees.division',
            'Workers Welfare Research Division (WWRD)'
        )
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)
        ->get();
    $apd = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where(
            'employees.division',
            'Advocacy and Pulications Division (APD)'
        )
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)        
        ->get();
    $fad = DB::table('users')
        ->join('employees', 'employees.employee_id', '=', 'users.id')
        ->select(
            'users.id',
            'employees.firstname',
            'employees.middlename',
            'employees.lastname',
            'employees.extname',
            'employees.position'
        )
        ->where(
            'employees.division',
            'Finance and Administrative Division (FAD)'
        )
        ->where('users.is_disabled',0)
        ->where('users.id','!=',auth()->user()->id)
        ->get();
    // return view('admin.attendance.index')->with('attendance',$attendance);
    return view(
        'admin.attendanceobao.index',
        compact('attendance', 'oed', 'erd', 'lssrd', 'wwrd', 'apd', 'fad')
    );
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
     * @param  \App\Models\ObaoAttendances  $obaoAttendances
     * @return \Illuminate\Http\Response
     */
    public function show(ObaoAttendances $obaoAttendances)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ObaoAttendances  $obaoAttendances
     * @return \Illuminate\Http\Response
     */
    public function edit(ObaoAttendances $obaoAttendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ObaoAttendances  $obaoAttendances
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ObaoAttendances $obaoAttendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ObaoAttendances  $obaoAttendances
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObaoAttendances $obaoAttendances)
    {
        //
    }

    public function destroyattendance(Request $request)
    {
        //Find a user with a given id and delete
        $id = $request->input('delete_attendance_id');
        DB::beginTransaction();
        try {
            ObaoAttendances::where('attendance_id', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            //rollback all transactions made to database
            DB::rollback();
            $log = new ErrorLog();
            $log->message = $e->getMessage();
            $log->file = $e->getFile();
            $log->line = $e->getLine();
            $log->url = $_SERVER['REQUEST_URI'];
            $log->save();
            //redirect back to home with error
            return redirect('/manageattendanceobao')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        return redirect('manageattendanceobao')->with(
            'success',
            'record successfully deleted.'
        );
    }

}
