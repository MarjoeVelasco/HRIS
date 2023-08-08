<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Attendance;
use App\Other_attendances;
use App\Employee;
use App\User;
use DB;
use DateTime;
use Carbon\Carbon;
use App\ErrorLog;


class OtherAttendancesController extends Controller
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
        ->join('other_attendances', 'other_attendances.employee_id', '=', 'users.id')
        ->select(
            'users.name',
            'users.id',
            'other_attendances.attendance_id',
            'other_attendances.time_in',
            'other_attendances.status',
            'other_attendances.time_status'
        )
        ->orderBy('other_attendances.time_in', 'DESC')
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
        'admin.attendanceother.index',
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
     * @param  \App\Models\other_attendances  $other_attendances
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\other_attendances  $other_attendances
     * @return \Illuminate\Http\Response
     */
    public function edit(other_attendances $other_attendances)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\other_attendances  $other_attendances
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, other_attendances $other_attendances)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\other_attendances  $other_attendances
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        //
        $attendance = DB::table('users')
            ->join('other_attendances', 'other_attendances.employee_id', '=', 'users.id')
            ->select(
                'users.name',
                'other_attendances.attendance_id',
                'other_attendances.time_in',
                'other_attendances.status',
                'other_attendances.time_status'
            )
            ->whereBetween(DB::raw('DATE(time_in)'), [
                $request->input('from'),
                $request->input('to'),
            ])
            ->get();
        $oed = DB::table('users')
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
                'Office of the Executive Director (OED)'
            )
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
            ->get();
        // return view('admin.attendance.index')->with('attendance',$attendance);
        return view(
            'admin.attendanceother.index',
            compact('attendance', 'oed', 'erd', 'lssrd', 'wwrd', 'apd', 'fad')
        );
    }

    public function destroy(other_attendances $other_attendances)
    {
        //
    }

    public function destroyattendance(Request $request)
    {
        //Find a user with a given id and delete
        $id = $request->input('delete_attendance_id');
        DB::beginTransaction();
        try {
            Other_attendances::where('attendance_id', $id)->delete();
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
            return redirect('/manageattendanceothers')->with(
                'error',
                'Execution Error. Record Not Saved!'
            );
        }
        return redirect('manageattendanceothers')->with(
            'success',
            'record successfully deleted.'
        );
    }

}
